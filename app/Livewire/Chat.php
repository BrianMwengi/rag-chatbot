<?php

namespace App\Livewire;

use App\Ai\Agents\DocumentQA;
use App\Models\Document;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Chat extends Component
{
    public Document $document;

    /** 
     * UI State 
     */
    public string $activeTab = 'chat';
    
    public array $selectedSources = [];

    /** 
     * Chat History
     * @var array<int, array{role: string, content: string, sources?: array}> 
     */
    public array $messages = [
        // Hardcoded sample to match your image initially
        [
            'role' => 'user', 
            'content' => 'How do I install pgvector?'
        ],
        [
            'role' => 'assistant', 
            'content' => "To install pgvector, run:\n\"CREATE EXTENSION vector;\"\n.", 
            'sources' => [1] // Reference to source ID
        ]
    ];

    #[Validate('required|string|max:1000')]
    public string $query = '';

    public function mount(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Switch between Chat, Sources, and Settings tabs
     */
    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * Handle the form submission
     */
    public function sendMessage(): void
    {
        $this->validate();

        // 1. Add User Message to UI immediately
        $this->messages[] = ['role' => 'user', 'content' => $this->query];

        // 2. Call the AI Agent (Using Povilas's pattern)
        // We pass the document context ID so the AI knows what to search
        $response = (new DocumentQA($this->document->store_id))->prompt($this->query);

        // 3. Add AI Response to UI
        $this->messages[] = [
            'role' => 'assistant', 
            'content' => $response->text,
            // Assuming the response object might return used source IDs
            'sources' => $response->source_ids ?? [] 
        ];

        // 4. Reset Input
        $this->query = '';
    }

    /**
     * Triggered when clicking "Reference: [Source 1]"
     */
    public function highlightSource(int $sourceId): void
    {
        // You can dispatch a browser event here to highlight text in the UI via AlpineJS
        $this->dispatch('highlight-citation', sourceId: $sourceId);
    }

    /**
     * Triggered when clicking "View Full PDF"
     */
    public function viewFullPdf(int $sourceId): void
    {
        // Logic to open the PDF viewer, potentially in a modal or new tab
        $this->dispatch('open-pdf-viewer', sourceId: $sourceId);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
