@extends('layouts.app')

@section('title', 'Knowledge Base Admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Local Vector Stores</h1>
        <a href="{{ route('knowledge-bases.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 dark:bg-white px-4 py-2 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Upload Document
        </a>
    </div>

    @if ($knowledgeBases->isEmpty())
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No knowledge bases created yet.</p>
            <a href="{{ route('knowledge-bases.create') }}" class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:underline">Upload your first one</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($knowledgeBases as $knowledgeBase)
                <a href="{{ route('knowledge-bases.show', $knowledgeBase) }}" wire:key="knowledge-base-{{ $knowledgeBase->id }}" class="group block rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-5">
                        <h2 class="font-semibold truncate group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">{{ $knowledgeBase->title }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $knowledgeBase->original_filename }}</p>
                        @if ($knowledgeBase->isGenerating())
                            <p class="mt-3 text-xs text-gray-400 dark:text-gray-500">Indexing document...</p>
                        @elseif ($knowledgeBase->isCompleted())
                            <p class="mt-3 text-xs text-emerald-600 dark:text-emerald-400">Ready for questions</p>
                        @elseif ($knowledgeBase->isFailed())
                            <p class="mt-3 text-xs text-red-600 dark:text-red-400">Indexing failed</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection