<?php

namespace App\Livewire\Chat;

use App\Models\KnowledgeBase;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Knowledge Base Assistant')]
class ChatIndex extends Component
{
    public function render(): View
    {
        return view('livewire.chat.chat-index', [
            'knowledgeBases' => KnowledgeBase::query()
                ->where('status', 'completed')
                ->latest()
                ->get(),
        ]);
    }
}