<?php

namespace App\Jobs;

use App\Models\KnowledgeBase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class IndexKnowledgeBaseJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public KnowledgeBase $knowledgeBase) {}

    public function handle(): void
    {
        // Actual PDF parsing + embedding logic comes in the RAG module
        // For now we simulate a successful index
        $this->knowledgeBase->update(['status' => 'completed']);
    }
}
