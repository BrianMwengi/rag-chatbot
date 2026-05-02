<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKnowledgeBaseRequest;
use App\Jobs\IndexKnowledgeBaseJob;
use App\Models\KnowledgeBase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class KnowledgeBaseController extends Controller
{
    public function index(): View
    {
        $knowledgeBases = KnowledgeBase::query()->latest()->get();

        return view('knowledge-bases.index', [
            'knowledgeBases' => $knowledgeBases,
        ]);
    }

    public function create(): View
    {
        return view('knowledge-bases.create');
    }

    public function store(StoreKnowledgeBaseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $file = $request->file('document_file');
        $filePath = $file->store('knowledge-bases', 'public');

        $knowledgeBase = KnowledgeBase::query()->create([
            'title' => $validated['title'],
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'status' => 'generating',
        ]);

        IndexKnowledgeBaseJob::dispatch($knowledgeBase);

        return redirect()->route('knowledge-bases.show', $knowledgeBase);
    }

    public function show(KnowledgeBase $knowledgeBase): View
    {
        return view('knowledge-bases.show', [
            'knowledgeBase' => $knowledgeBase,
        ]);
    }
}
