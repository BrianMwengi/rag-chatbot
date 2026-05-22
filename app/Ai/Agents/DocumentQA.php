<?php

namespace App\Ai\Agents;

use App\Ai\Tools\LocalVectorSearch;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Promptable;

#[Provider('gemini')]
class DocumentQA implements Agent, HasTools
{
    use Promptable;

    public function __construct(public string $knowledgeBaseId) {}

    public function instructions(): string
    {
        return <<<'INSTRUCTIONS'
        You are a document Q&A assistant. Answer questions based only on the context provided by your search tool.
        Rules:
        - Only answer based on information found in the tool context.
        - Be concise and direct.
        - If the context does not contain the answer, say "I cannot find that information in the uploaded document."
        INSTRUCTIONS;
    }

    public function tools(): iterable
    {
        return [
            new LocalVectorSearch($this->knowledgeBaseId),
        ];
    }
}

