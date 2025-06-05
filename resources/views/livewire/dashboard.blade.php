<div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <livewire:components.sidebar :activeTab="$activeTab" />

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col">
        <!-- Top Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900 capitalize">{{ $activeTab }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">{{ auth()->user()->name }}</span>
                        <button 
                            wire:click="logout"
                            class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ $isLoggingOut ? 'disabled' : '' }}
                        >
                            @if($isLoggingOut)
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Logging out...
                            @else
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                Logout
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @if(session('success') || session('error'))
            <div class="bg-white border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 rounded-md p-4" x-data x-show="true" x-transition x-init="setTimeout(() => $el.remove(), 5000)">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-md p-4" x-data x-show="true" x-transition x-init="setTimeout(() => $el.remove(), 5000)">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @if($activeTab === 'dashboard')
                    <!-- Dashboard Content -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to your Dashboard!</h2>
                            <p class="text-gray-600 mb-6">You have successfully logged in to the application.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Stats Cards -->
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <div class="ml-4">
                                            <p class="text-blue-200">Available Tests</p>
                                            <p class="text-2xl font-bold">{{ \App\Models\Test::has('questions')->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="ml-4">
                                            <p class="text-green-200">Completed Tests</p>
                                            <p class="text-2xl font-bold">{{ auth()->user()->submissions()->count() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <div class="ml-4">
                                            <p class="text-purple-200">Your Role</p>
                                            <p class="text-lg font-bold">{{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Name:</span>
                                    <span class="font-medium">{{ auth()->user()->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Email:</span>
                                    <span class="font-medium">{{ auth()->user()->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Member Since:</span>
                                    <span class="font-medium">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($activeTab === 'tests')
                    <!-- Tests Content -->
                    <livewire:components.tests-list />
                @endif
            </div>
        </main>
    </div>
</div> 