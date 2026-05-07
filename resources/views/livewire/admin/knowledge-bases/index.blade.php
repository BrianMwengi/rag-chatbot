@extends('layouts.app')

@section('title', 'Knowledge Base Admin')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Knowledge Base Admin</h1>
        <a href="{{ route('knowledge-bases.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 dark:bg-white px-4 py-2 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
            <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Upload New Document
        </a>
    </div>

    @if ($knowledgeBases->isEmpty())
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No knowledge bases created yet.</p>
            <a href="{{ route('knowledge-bases.create') }}" class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:underline">Upload your first one</a>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Document Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($knowledgeBases as $knowledgeBase)
                        <tr wire:key="kb-row-{{ $knowledgeBase->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $knowledgeBase->title }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $knowledgeBase->original_filename }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($knowledgeBase->isGenerating())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-600"></span>
                                        Indexing...
                                    </span>
                                @elseif ($knowledgeBase->isCompleted())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        Ready
                                    </span>
                                @elseif ($knowledgeBase->isFailed())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('knowledge-bases.show', $knowledgeBase) }}" class="text-sm font-medium text-violet-600 hover:text-violet-500 dark:text-violet-400">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
