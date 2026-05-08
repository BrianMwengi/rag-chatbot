<?php

namespace App\Livewire\Admin;

use App\Jobs\IndexKnowledgeBaseJob;
use App\Models\KnowledgeBase;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class KnowledgeBaseCreate extends Component
{
    use WithFileUploads;

    public string $title = '';

    public $documentFile;

    protected array $rules = [
        'title' => 'required|string|max:255',
        'documentFile' => 'required|file|mimes:pdf,txt|max:10240',
    ];

    public function save(): void
    {
        $this->validate();

        $filePath = $this->documentFile->store('knowledge-bases', 'public');

        $knowledgeBase = KnowledgeBase::query()->create([
            'title' => $this->title,
            'original_filename' => $this->documentFile->getClientOriginalName(),
            'file_path' => $filePath,
            'status' => 'generating',
        ]);

        IndexKnowledgeBaseJob::dispatch($knowledgeBase);

        $this->redirect(route('knowledge-bases.show', $knowledgeBase), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.admin.knowledge-base-create');
    }
}
