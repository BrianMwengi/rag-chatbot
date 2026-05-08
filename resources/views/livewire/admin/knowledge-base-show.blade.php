<div>
    <div class="mb-8">
        <a href="{{ route('knowledge-bases.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Back to Knowledge Base
        </a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">{{ $knowledgeBase->title }}</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $knowledgeBase->original_filename }}</p>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-8">
        <p class="text-sm text-gray-500">Status:
            <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $knowledgeBase->status }}</span>
        </p>
        @if ($knowledgeBase->isGenerating())
            <p class="mt-4 text-sm text-blue-600 animate-pulse">⏳ Indexing in progress... refresh in a moment.</p>
        @elseif ($knowledgeBase->isCompleted())
            <p class="mt-4 text-sm text-emerald-600">✅ Document indexed and ready for queries.</p>
        @elseif ($knowledgeBase->isFailed())
            <p class="mt-4 text-sm text-red-600">❌ Indexing failed. Try uploading again.</p>
        @endif
    </div>
</div>