<div class="max-w-sm mx-auto mt-20">
    <div class="border border-gray-200 dark:border-gray-800 rounded-xl p-8 bg-white dark:bg-gray-900 shadow-sm">
        <h1 class="text-xl font-bold mb-6">Admin Access</h1>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input
                    wire:model="password"
                    type="password"
                    placeholder="Enter admin password"
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-transparent px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-violet-500"
                />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-gray-900 dark:bg-white px-4 py-2 text-sm font-medium text-white dark:text-gray-900 hover:bg-gray-700 transition-colors">
                <span wire:loading.remove>Login</span>
                <span wire:loading>Checking...</span>
            </button>
        </form>
    </div>
</div>