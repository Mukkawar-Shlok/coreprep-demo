<div class="bg-gray-800 text-white {{ $isCollapsed ? 'w-16' : 'w-64' }} min-h-screen transition-all duration-300 ease-in-out flex flex-col">
    <!-- Header with toggle button -->
    <div class="p-4 border-b border-gray-700">
        <div class="flex items-center justify-between">
            @if(!$isCollapsed)
                <div>
                    <h2 class="text-xl font-bold text-white">Dashboard</h2>
                    <p class="text-gray-400 text-sm">Welcome back!</p>
                </div>
            @endif
            
            <!-- Simple Toggle Button -->
            <button 
                wire:click="toggleSidebar"
                class="p-2 rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200"
                title="Toggle Sidebar"
            >
                @if($isCollapsed)
                    <!-- Expand icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                @else
                    <!-- Collapse icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                @endif
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2">
        <!-- Dashboard Tab -->
        <button
            wire:click="setActiveTab('dashboard')"
            class="w-full flex items-center px-4 py-3 text-left rounded-lg transition-all duration-200 {{ $activeTab === 'dashboard' ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} {{ $isCollapsed ? 'justify-center' : '' }}"
            title="{{ $isCollapsed ? 'Dashboard' : '' }}"
        >
            <svg class="w-5 h-5 {{ $isCollapsed ? '' : 'mr-3' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
            </svg>
            @if(!$isCollapsed)
                <span class="font-medium">Dashboard</span>
            @endif
        </button>

        <!-- Tests Tab -->
        <button
            wire:click="setActiveTab('tests')"
            class="w-full flex items-center px-4 py-3 text-left rounded-lg transition-all duration-200 {{ $activeTab === 'tests' ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} {{ $isCollapsed ? 'justify-center' : '' }}"
            title="{{ $isCollapsed ? 'Tests' : '' }}"
        >
            <svg class="w-5 h-5 {{ $isCollapsed ? '' : 'mr-3' }} flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            @if(!$isCollapsed)
                <span class="font-medium">Tests</span>
            @endif
        </button>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-700 mt-auto">
        <div class="flex items-center {{ $isCollapsed ? 'justify-center' : '' }}">
            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </span>
            </div>
            
            @if(!$isCollapsed)
                <div class="ml-3 min-w-0 flex-1">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            @endif
        </div>
    </div>
</div> 