<div class="w-full max-w-4xl mx-auto p-6">
    <div class="border-b border-gray-200 dark:border-gray-800 pb-4 mb-6">
        <h1 class="font-bold text-2xl text-gray-900 dark:text-white">Knowledge Base Assistant</h1>
        <p class="text-sm text-gray-500 mt-1">Select an available document to start your AI-powered chat session.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse ($knowledgeBases as $kb)
            <div class="border border-gray-200 dark:border-gray-800 rounded-lg p-5 bg-white dark:bg-gray-900 shadow-sm flex flex-col justify-between">
                <div>
                    <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 truncate">{{ $kb->title }}</h2>
                    <p class="text-xs text-gray-400 mt-1 truncate">{{ $kb->original_filename }}</p>
                    <p class="text-xs text-gray-500 mt-4 flex items-center">
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block mr-2"></span>
                        Ready for Questions
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                    <a href="{{ route('chat', $kb) }}" wire:navigate
                        class="px-4 py-1.5 border border-gray-900 dark:border-white rounded bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-medium transition-colors">
                        Chat with Document →
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 dark:text-gray-400 font-medium">No documents available yet.</p>
                <p class="text-sm text-gray-400 mt-1">No documents have been indexed yet.</p>
            </div>
        @endforelse
    </div>
</div>