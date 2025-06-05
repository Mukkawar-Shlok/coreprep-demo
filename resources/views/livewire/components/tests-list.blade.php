<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Available Tests</h3>
            <p class="mt-1 text-sm text-gray-500">Select a test to get started • Tests can only be taken once</p>
        </div>
    </div>

    <!-- Search -->
    <div class="max-w-lg">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                wire:model.live="search"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Search tests..."
            >
        </div>
    </div>

    <!-- Tests Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($tests as $test)
            @php
                $isCompleted = in_array($test->id, $completedTestIds);
                $testResult = $testResults[$test->id] ?? null;
            @endphp
            
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-md transition-shadow duration-200 {{ $isCompleted ? 'opacity-75' : '' }}">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $isCompleted ? 'bg-gray-500' : 'bg-indigo-500' }} rounded-lg flex items-center justify-center">
                                @if($isCompleted)
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Test</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $test->title }}</dd>
                            </dl>
                        </div>
                    </div>
                    
                    @if($test->description)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 line-clamp-3">{{ $test->description }}</p>
                        </div>
                    @endif

                    <!-- Test Results (if completed) -->
                    @if($isCompleted && $testResult)
                        <div class="mt-4 p-3 bg-gray-50 rounded-md">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Your Result:</span>
                                <span class="font-medium {{ $testResult->result === 'pass' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ ucfirst($testResult->result) }}
                                </span>
                            </div>
                            <div class="mt-1 text-xs text-gray-500">
                                {{ $testResult->correct_ans_count }} correct, {{ $testResult->wrong_ans_count }} wrong
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $test->total_questions }} question{{ $test->total_questions !== 1 ? 's' : '' }}
                        </div>
                        
                        @if($isCompleted)
                            <span class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-gray-600 bg-gray-100 cursor-not-allowed">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Completed
                            </span>
                        @else
                            <button
                                wire:click="startTest({{ $test->id }})"
                                type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                            >
                                Start Test
                            </button>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center justify-between">
                        @if($isCompleted)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $testResult && $testResult->result === 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $testResult && $testResult->result === 'pass' ? 'Passed' : 'Failed' }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Available
                            </span>
                        @endif

                        @if($isCompleted)
                            <span class="text-xs text-gray-500">
                                Taken {{ $testResult ? $testResult->created_at->format('M j, Y') : 'recently' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No tests found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search)
                            No tests match your search criteria.
                        @else
                            There are no active tests available at the moment.
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($tests->hasPages())
        <div class="mt-6">
            {{ $tests->links() }}
        </div>
    @endif
</div> 