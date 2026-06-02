<?php

namespace Tests\Feature\Jobs;

use Tests\TestCase;
use App\Jobs\IndexKnowledgeBaseJob;
use App\Models\KnowledgeBase;
use App\Services\Document\PdfParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Enums\Lab;
use Mockery;
use Exception;

class IndexKnowledgeBaseJobTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        // Clean up Mockery aliases after each test runs
        Mockery::close();
        parent::tearDown();
    }

    public function test_it_indexes_pdf_content_successfully(): void
    {
        // 1. Arrange: Setup Storage and Database
        Storage::fake('public');
        Storage::disk('public')->put('manuals/guide.pdf', 'dummy content');

        $knowledgeBase = KnowledgeBase::factory()->create([
            'file_path' => 'manuals/guide.pdf',
            'status' => 'pending',
        ]);

        // 2. Mock the PdfParserService
        $pdfParser = $this->mock(PdfParserService::class, function ($mock) {
            $mock->shouldReceive('extractText')
                ->once()
                ->andReturn("Paragraph One.\n\nParagraph Two.");
        });

        // 3. Mock the Laravel\Ai\Embeddings static class using an Alias Mock
        // Generate valid mock embeddings containing exactly 768 elements each
        $fakeResponse = (object) [
            'embeddings' => [
                array_fill(0, 768, 0.1), // Creates a float array of 768 dimensions
                array_fill(0, 768, 0.4), // Creates a float array of 768 dimensions
            ]
        ];


        $embeddingsMock = Mockery::mock('alias:Laravel\Ai\Embeddings');
        
        $embeddingsMock->shouldReceive('for')
            ->once()
            ->with(['Paragraph One.', 'Paragraph Two.'])
            ->andReturnSelf();

        $embeddingsMock->shouldReceive('dimensions')
            ->once()
            ->with(768)
            ->andReturnSelf();

        $embeddingsMock->shouldReceive('generate')
            ->once()
            ->with(Lab::Gemini)
            ->andReturn($fakeResponse);

        // 4. Act: Dispatch the job synchronously
        (new IndexKnowledgeBaseJob($knowledgeBase))->handle($pdfParser);

        // 5. Assert: Verify Database Changes
        $this->assertDatabaseHas('knowledge_bases', [
            'id' => $knowledgeBase->id,
            'status' => 'completed',
            'error_message' => null,
        ]);
    }

    public function test_it_handles_pdf_parsing_failures(): void
    {
        // 1. Arrange
        Storage::fake('public');
        $knowledgeBase = KnowledgeBase::factory()->create(['file_path' => 'bad.pdf']);

        // 2. Mock Parser to Throw Exception
        $pdfParser = $this->mock(PdfParserService::class, function ($mock) {
            $mock->shouldReceive('extractText')
                ->once()
                ->andThrow(new Exception('Corrupted PDF file'));
        });

        // 3. Mock the Embeddings alias and make sure "for" is never called
        $embeddingsMock = Mockery::mock('alias:Laravel\Ai\Embeddings');
        $embeddingsMock->shouldReceive('for')->never();

        // 4. Act
        (new IndexKnowledgeBaseJob($knowledgeBase))->handle($pdfParser);

        // 5. Assert
        $this->assertDatabaseHas('knowledge_bases', [
            'id' => $knowledgeBase->id,
            'status' => 'failed',
            'error_message' => 'Corrupted PDF file',
        ]);
    }
}

