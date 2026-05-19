<div class="w-full max-w-5xl mx-auto p-4 border border-black font-sans text-sm">
    <!-- Header -->
    <div class="border-b border-black pb-2 mb-4">
        <h1 class="font-bold text-base">RAG Workbench (Agent Mode)</h1>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Left Side: Chat Panel -->
        <div class="flex flex-col space-y-4">
            <!-- Tabs -->
            <div class="flex space-x-2 border-b border-gray-300 pb-1">
                <button wire:click="setTab('chat')" class="font-bold underline px-1">Chat Stream</button>
                <span class="text-gray-400">|</span>
                <button wire:click="setTab('sources')" class="px-1 text-gray-600 hover:underline">Sources</button>
                <span class="text-gray-400">|</span>
                <button wire:click="setTab('settings')" class="px-1 text-gray-600 hover:underline">Settings</button>
            </div>

            <!-- Messages Stream -->
            <div class="flex-1 min-h-[200px] space-y-2">
                <div>
                    <span class="font-bold">User:</span> How do I install pgvector?
                </div>
                
                <div class="space-y-1">
                    <div><span class="font-bold">[Bot]:</span> To install pgvector, run:</div>
                    <div><span class="font-bold">[Bot]:</span> "CREATE EXTENSION vector;"</div>
                    <div><span class="font-bold">[Bot]:</span> .</div>
                </div>

                <!-- Reference Pill Button -->
                <div class="pt-2">
                    <button wire:click="highlightSource(1)" class="inline-flex items-center px-4 py-1 border-2 border-black rounded-full font-semibold bg-gray-100 hover:bg-gray-200 transition-colors">
                        Bot]: Reference: [Source 1
                    </button>
                </div>
            </div>

            <!-- Input Form Footer -->
            <form wire:submit.prevent="sendMessage" class="flex items-center space-x-2 pt-4">
                <input 
                    wire:model="query" 
                    type="text" 
                    placeholder="Type your query here..." 
                    class="flex-1 border-b border-black py-1 px-2 focus:outline-none bg-transparent"
                />
                <button type="submit" class="px-4 py-1 border border-black rounded bg-white hover:bg-gray-100 transition-colors">
                    Send
                </button>
            </form>
        </div>

        <!-- Right Side: Context Viewer -->
        <div class="border-l border-transparent md:border-gray-200 md:pl-6 space-y-4">
            <h2 class="font-bold text-base mb-2">Context Viewer</h2>

            <!-- Checkbox Source Selector -->
            <div class="flex items-start space-x-2 font-medium">
                <input 
                    type="checkbox" 
                    id="source-1" 
                    wire:model.live="selectedSources" 
                    value="1" 
                    class="mt-1 accent-black border border-black"
                />
                <label href="#" for="source-1" class="cursor-pointer hover:underline">
                    Source 1: installation_guide.pdf
                </label>
            </div>

            <!-- Text Snippet Previews -->
            <div class="space-y-3 pt-2 text-gray-700">
                <div class="border-b border-black pb-1 italic">
                    ...run the SQL command to enable...
                </div>
                <div class="border-b border-black pb-1 italic">
                    ...the vector extension on DB...
                </div>
            </div>

            <!-- View Full Document Action -->
            <div class="pt-4">
                <button wire:click="viewFullPdf(1)" class="px-4 py-1.5 border-2 border-black rounded font-semibold bg-gray-100 hover:bg-gray-200 transition-colors">
                    View Full PDF
                </button>
            </div>
        </div>

    </div>
</div>
