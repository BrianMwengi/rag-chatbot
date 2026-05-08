<?php

namespace App\Livewire\Admin;

use App\Models\KnowledgeBase;
use Illuminate\Contracts\View\View;
use Livewire\Component;

#[Layout('layouts.app')]
class KnowledgeBaseShow extends Component
{
    public KnowledgeBase $knowledgeBase;

    public function render(): View
    {
        return view('livewire.admin.knowledge-base-show', [
            'knowledgeBase' => $this->knowledgeBase,
        ]);
    }
}
