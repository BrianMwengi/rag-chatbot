<?php

namespace App\Jobs;

use App\Models\KnowledgeBase;
use App\Services\Document\PdfParserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;
use Throwable;

class IndexKnowledgeBaseJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public KnowledgeBase $knowledgeBase) {}

    public function handle(PdfParserService $pdfParser): void
    {
        try {
            $filePath = Storage::disk('public')->path($this->knowledgeBase->file_path);
            $content = $pdfParser->extractText($filePath);

            $chunks = $this->chunkText($content);

            $response = Embeddings::for($chunks)->dimensions(768)->generate(Lab::Gemini);

            foreach ($chunks as $index => $chunkText) {
                $this->knowledgeBase->chunks()->create([
                    'content' => $chunkText,
                    'embedding' => $response->embeddings[$index],
                ]);
            }

            $this->knowledgeBase->update([
                'status' => 'completed',
                'error_message' => null,
            ]);

        } catch (Throwable $throwable) {
            report($throwable);

            $this->knowledgeBase->update([
                'status' => 'failed',
                'error_message' => $throwable->getMessage(),
            ]);
        }
    }

    /**
     * Split text into chunks by paragraphs, capping at ~1000 characters.
     *
     * @return array<int, string>
     */
    private function chunkText(string $content): array
    {
        $paragraphs = preg_split('/\n\n+/', $content);
        $paragraphs = array_filter($paragraphs, fn (string $p) => trim($p) !== '');
        $paragraphs = array_values($paragraphs);

        $chunks = [];

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);

            if (strlen($paragraph) <= 1000) {
                $chunks[] = $paragraph;

                continue;
            }

            $sentences = preg_split('/(?<=[.!?])\s+/', $paragraph);
            $currentChunk = '';

            foreach ($sentences as $sentence) {
                if ($currentChunk !== '' && strlen($currentChunk.' '.$sentence) > 1000) {
                    $chunks[] = $currentChunk;
                    $currentChunk = $sentence;
                } else {
                    $currentChunk = $currentChunk !== '' ? $currentChunk.' '.$sentence : $sentence;
                }
            }

            if ($currentChunk !== '') {
                $chunks[] = $currentChunk;
            }
        }

        return $chunks;
    }
}
