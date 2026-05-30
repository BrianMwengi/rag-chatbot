<?php

namespace Tests\Unit\Services\Document;

use App\Services\Document\PdfParserService;
use Exception;
use PHPUnit\Framework\TestCase;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser;

class PdfParserServiceTest extends TestCase
{
    public function test_it_successfully_extracts_text_from_a_pdf(): void
    {
        // 1. Arrange
        $filePath = '/path/to/mock/document.pdf';
        $expectedText = 'Hello, this is parsed text.';

        // Mock the Document object returned by the parser
        $mockDocument = $this->createMock(Document::class);
        $mockDocument->expects($this->once())
            ->method('getText')
            ->willReturn($expectedText);

        // Mock the Parser itself
        $mockParser = $this->createMock(Parser::class);
        $mockParser->expects($this->once())
            ->method('parseFile')
            ->with($filePath)
            ->willReturn($mockDocument);

        // Inject the mocked parser into your service
        $service = new PdfParserService($mockParser);

        // 2. Act
        $result = $service->extractText($filePath);

        // 3. Assert
        $this->assertEquals($expectedText, $result);
    }

    public function test_it_throws_a_custom_exception_when_parsing_fails(): void
    {
        // 1. Arrange
        $filePath = '/path/to/corrupt/document.pdf';
        
        $mockParser = $this->createMock(Parser::class);
        $mockParser->expects($this->once())
            ->method('parseFile')
            ->with($filePath)
            ->willThrowException(new Exception('Invalid format'));

        $service = new PdfParserService($mockParser);

        // 3. Assert (Expected exception must be declared BEFORE the action)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Failed to parse PDF: Invalid format');

        // 2. Act
        $service->extractText($filePath);
    }
}



