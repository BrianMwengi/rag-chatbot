<div>
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Knowledge Base Admin</h1>
        <a href="{{ route('knowledge-bases.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-gray-900 dark:bg-white px-4 py-2 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Upload New Document
        </a>
    </div>

    @if ($knowledgeBases->isEmpty())
        <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400 mb-4">No documents uploaded yet.</p>
            <a href="{{ route('knowledge-bases.create') }}" class="text-sm font-medium text-violet-600 dark:text-violet-400 hover:underline">
                Upload your first one
            </a>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">File Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Chunks</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @foreach ($knowledgeBases as $knowledgeBase)
                        <tr wire:key="kb-row-{{ $knowledgeBase->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $knowledgeBase->title }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $knowledgeBase->original_filename }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $knowledgeBase->created_at->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($knowledgeBase->isGenerating())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 dark:bg-blue-900/30 px-2 py-1 text-xs font-medium text-blue-700 dark:text-blue-400">
                                        <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-blue-600"></span>
                                        Processing
                                    </span>
                                @elseif ($knowledgeBase->isCompleted())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 px-2 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>
                                        Indexed
                                    </span>
                                @elseif ($knowledgeBase->isFailed())
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 dark:bg-red-900/30 px-2 py-1 text-xs font-medium text-red-700 dark:text-red-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-600"></span>
                                        Failed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{-- Populated in RAG module when chunking is built --}}
                                —
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button
                                    wire:click="delete({{ $knowledgeBase->id }})"
                                    wire:confirm="Are you sure you want to delete this document?"
                                    class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Refresh Status — useful when queue worker is running --}}
        <div class="mt-4 flex justify-start">
            <button wire:click="$refresh" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Refresh Status
            </button>
        </div>
    @endif
</div>