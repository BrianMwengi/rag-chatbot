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
        $this->knowledgeBase = $knowledgeBase;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function sendMessage(): void
    {
        $this->validate();

        $this->messages[] = [
            'role'    => 'user',
            'content' => $this->query,
        ];

        $response = (new DocumentQA((string) $this->knowledgeBase->id))
            ->prompt($this->query);

        $this->messages[] = [
            'role'    => 'assistant',
            'content' => $response->text,
        ];

        $this->query = '';
    }

    public function highlightSource(int $sourceId): void
    {
        $this->dispatch('highlight-citation', sourceId: $sourceId);
    }

    public function render()
    {
        return view('livewire.chat.chat-interface');
    }
}