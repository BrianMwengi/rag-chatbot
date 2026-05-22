<div class="w-full max-w-5xl mx-auto p-4">
    <div class="border-b border-gray-200 dark:border-gray-800 pb-2 mb-4">
        <h1 class="font-bold text-base">RAG Workbench — {{ $knowledgeBase->title }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Left: Chat Panel --}}
        <div class="flex flex-col space-y-4">

            {{-- Tabs --}}
            <div class="flex space-x-2 border-b border-gray-300 dark:border-gray-700 pb-1">
                <button wire:click="setTab('chat')"
                    class="{{ $activeTab === 'chat' ? 'font-bold underline' : 'text-gray-500 hover:underline' }}">
                    Chat Stream
                </button>
                <span class="text-gray-400">|</span>
                <button wire:click="setTab('sources')"
                    class="{{ $activeTab === 'sources' ? 'font-bold underline' : 'text-gray-500 hover:underline' }}">
                    Sources
                </button>
            </div>

            {{-- Tab: Chat --}}
            @if ($activeTab === 'chat')
                <div class="flex-1 min-h-[300px] space-y-3">
                    @forelse ($messages as $message)
                        <div>
                            <span class="font-bold">
                                {{ $message['role'] === 'user' ? 'You' : 'Bot' }}:
                            </span>
                            <span class="whitespace-pre-wrap">{{ $message['content'] }}</span>
                        </div>
                    @empty
                        <p class="text-gray-400 italic">Ask a question about your document...</p>
                    @endforelse

                    <div wire:loading wire:target="sendMessage" class="text-blue-500 animate-pulse text-sm">
                        Thinking...
                    </div>
                </div>
            @endif

            {{-- Tab: Sources --}}
            @if ($activeTab === 'sources')
                <div class="flex-1 min-h-[300px] space-y-3">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Document: {{ $knowledgeBase->original_filename }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Chunks indexed: {{ $knowledgeBase->chunks()->count() }}
                    </p>
                    @forelse ($knowledgeBase->chunks as $chunk)
                        <div class="border-b border-gray-200 dark:border-gray-800 pb-2 text-sm text-gray-600 dark:text-gray-400 italic">
                            {{ Str::limit($chunk->content, 150) }}
                        </div>
                    @empty
                        <p class="text-gray-400 italic">No chunks found.</p>
                    @endforelse
                </div>
            @endif

            {{-- Input — always visible --}}
            @if ($activeTab === 'chat')
                <form wire:submit="sendMessage" class="flex items-center space-x-2 pt-4 border-t border-gray-200 dark:border-gray-800">
                    <input
                        wire:model="query"
                        type="text"
                        placeholder="Type your query here..."
                        class="flex-1 border-b border-gray-400 py-1 px-2 focus:outline-none bg-transparent"
                    />
                    <button type="submit"
                        class="px-4 py-1 border border-gray-900 dark:border-white rounded bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-sm font-medium">
                        <span wire:loading.remove wire:target="sendMessage">Send</span>
                        <span wire:loading wire:target="sendMessage">...</span>
                    </button>
                </form>
            @endif

        </div>

        {{-- Right: Context Viewer --}}
        <div class="border-l border-gray-200 dark:border-gray-800 pl-6 space-y-4">
            <h2 class="font-bold text-base">Context Viewer</h2>
            <p class="text-sm text-gray-500">
                Document: <span class="font-medium text-gray-900 dark:text-white">{{ $knowledgeBase->original_filename }}</span>
            </p>
            <p class="text-sm text-gray-500">
                Chunks indexed: <span class="font-medium text-gray-900 dark:text-white">{{ $knowledgeBase->chunks()->count() }}</span>
            </p>
            <p class="text-sm text-gray-400 italic mt-4">
                Source highlighting coming soon.
            </p>
        </div>

    </div>
</div>