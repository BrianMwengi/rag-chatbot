<?php

namespace App\Ai\Tools;

use App\Models\DocumentChunk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Embeddings;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Tools\Request;

class LocalVectorSearch implements Tool
{
    public function __construct(protected string $knowledgeBaseId) {}

    public function description(): string
    {
        return 'Search the uploaded document for relevant information matching the query.';
    }

    public function handle(Request $request): string
    {
        $query = $request['query'];

        $queryEmbedding = Embeddings::for([$query])
            ->dimensions(768)
            ->generate(Lab::Gemini)
            ->embeddings[0];

        $chunks = DocumentChunk::query()
            ->where('knowledge_base_id', $this->knowledgeBaseId)
            ->whereVectorSimilarTo('embedding', $queryEmbedding, minSimilarity: 0.1)
            ->limit(5)
            ->get();

        if ($chunks->isEmpty()) {
            return 'No matching context found in the document.';
        }

        return $chunks
            ->map(fn($chunk) => "[CHUNK ID: " . $chunk->id . "]\nContext:\n" . $chunk->content)
            ->implode("\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->required(),
        ];
    }
}
