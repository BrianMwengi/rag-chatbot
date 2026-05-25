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
        
        Citation Rules:
        - Every chunk in the tool context is prefixed with a "[CHUNK ID: X]".
        - For every fact or sentence you mention, you MUST append its chunk ID in brackets right after the text, exactly like this: [ID: X] (where X is the numerical ID).
        - If a sentence relies on multiple chunks, list them all: "This is a fact [ID: 10][ID: 12]."
        - Never hallucinate, change, or guess an ID. Only use IDs explicitly present in the search tool payload.

        General Rules:
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

