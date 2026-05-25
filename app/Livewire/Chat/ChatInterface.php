<?php

namespace App\Livewire\Chat;

use App\Ai\Agents\DocumentQA;
use App\Models\KnowledgeBase;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Chat')]
class ChatInterface extends Component
{
    public KnowledgeBase $knowledgeBase;

    public string $activeTab = 'chat';

    public array $selectedSources = [];

    /**
     * @var array<int, array{role: string, content: string, sources?: array}>
     */
    public array $messages = [];

    #[Validate('required|string|max:1000')]
    public string $query = '';

    public function mount(KnowledgeBase $knowledgeBase): void
    {
        $this->knowledgeBase = $knowledgeBase->load(['chunks' => function ($query) {
            $query->select('id', 'knowledge_base_id', 'content');
        }]);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function sendMessage(): void
    {
         set_time_limit(120);

        $this->validate();

        $this->messages[] = [
            'role'    => 'user',
            'content' => $this->query,
        ];

        $this->query = '';

        try {
            $response = (new DocumentQA((string) $this->knowledgeBase->id))
                ->prompt(end($this->messages)['content']);

            // dd($response->toolResults->toArray(), $response->steps->toArray());

            $botResponseText = $response->text;

            preg_match_all('/\[ID:\s*(\d+)\]/', $botResponseText, $matches);
            $citedChunkIds = array_map('intval', $matches[1] ?? []);

            $formattedContent = preg_replace(
                '/\[ID:\s*(\d+)\]/',
                '<button wire:click="highlightSource($1)" class="inline-flex items-center px-1.5 py-0.5 mx-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 cursor-pointer hover:bg-blue-200">Ref: $1</button>',
                $botResponseText
            );

            $this->messages[] = [
                'role'    => 'assistant',
                'content' => $formattedContent,
                'sources' => $citedChunkIds,
            ];

            $this->selectedSources = $citedChunkIds;

        } catch (\Throwable $e) {
            $this->messages[] = [
                'role'    => 'assistant',
                'content' => '⚠️ The AI is currently overloaded. Please wait a moment and try again.',
                'sources' => [],
            ];
        }
    }

    public function highlightSource(int $sourceId): void
    {
        $this->dispatch('highlight-citation', sourceId: $sourceId);
    }

    public function render()
    {
        return view('livewire.chat.chat-interface', [
            'chunksCount' => $this->knowledgeBase->chunks->count(),
        ]);
    }
}
