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

        // Generate embedding for the user query using same model as indexing
        $queryEmbedding = Embeddings::for([$query])
            ->dimensions(768)
            ->generate(Lab::Gemini)
            ->embeddings[0];

        // Search pgvector for closest matching chunks
        $chunks = DocumentChunk::query()
            ->where('knowledge_base_id', $this->knowledgeBaseId)
            ->whereVectorSimilarTo('embedding', $queryEmbedding)
            ->limit(5)
            ->get();

        if ($chunks->isEmpty()) {
            return 'No matching context found in the document.';
        }

        return $chunks
            ->map(fn($chunk) => "Context:\n" . $chunk->content)
            ->implode("\n\n");
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->required(),
        ];
    }
}
