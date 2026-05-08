<div>
    <div class="mb-8">
        <a href="{{ route('knowledge-bases.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Back to Knowledge Base
        </a>
        <h1 class="text-2xl font-bold tracking-tight mt-2">Upload New Document</h1>
    </div>

    <div class="rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-8 max-w-lg">
        <form wire:submit="save" class="space-y-6">
            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input wire:model="title" type="text" placeholder="e.g. Company Policy 2025"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500" />
                @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Document (PDF or TXT, max 10MB)</label>
                <input wire:model="documentFile" type="file" accept=".pdf,.txt"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100" />
                @error('documentFile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-gray-900 dark:bg-white px-4 py-2 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-700 transition-colors">
                <span wire:loading.remove>Upload & Index</span>
                <span wire:loading>Uploading...</span>
            </button>
        </form>
    </div>
</div>