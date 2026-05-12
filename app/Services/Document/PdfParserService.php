<?php

namespace App\Services\Document;

use Exception;
use Smalot\PdfParser\Parser;

class PdfParserService
{
    public function __construct(protected Parser $parser) {} // inject via constructor

    /**
     * @throws Exception
     */
    public function extractText(string $filePath): string
    {
        try {
            $pdf = $this->parser->parseFile($filePath);

            return $pdf->getText();
        } catch (Exception $e) {
            throw new Exception('Failed to parse PDF: '.$e->getMessage());
        }
    }
}
