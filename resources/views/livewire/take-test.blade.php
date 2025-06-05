@if($testCompleted)
    <!-- Test Completion Screen -->
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="max-w-lg w-full bg-white rounded-lg shadow-lg p-8 text-center">
            @php
                $sessionMessage = session('success');
                // Extract result from session message
                $isPass = str_contains(strtolower($sessionMessage), 'pass');
            @endphp
            
            <div class="w-16 h-16 {{ $isPass ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center mx-auto mb-4">
                @if($isPass)
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @else
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @endif
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Test Completed!</h2>
            <p class="text-gray-600 mb-6">{{ $isPass ? 'Congratulations! You passed the test.' : 'You did not pass this time. Keep practicing!' }}</p>
            
            @if(session('success'))
                <div class="bg-{{ $isPass ? 'green' : 'blue' }}-50 border border-{{ $isPass ? 'green' : 'blue' }}-200 rounded-md p-4 mb-6">
                    <div class="text-{{ $isPass ? 'green' : 'blue' }}-800 text-sm">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="flex space-x-4 mt-6">
                <button 
                    wire:click="backToDashboard" 
                    class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
                >
                    Back to Dashboard
                </button>
            </div>
        </div>
    </div>
@else
    <!-- Test Taking Interface -->
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">{{ $test->title }}</h1>
                        <p class="text-sm text-gray-500">Question {{ $currentQuestionIndex + 1 }} of {{ $totalQuestions }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-600">
                            Progress: {{ number_format($this->progressPercentage, 1) }}%
                        </div>
                        <button 
                            wire:click="exitTest" 
                            class="text-gray-500 hover:text-gray-700 transition-colors duration-200"
                        >
                            Exit Test
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white border-b">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-2">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" style="width: {{ $this->progressPercentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Progress Info Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-4 sticky top-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-4">Test Progress</h3>
                        
                        <!-- Progress Stats -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Current:</span>
                                <span class="font-medium">{{ $currentQuestionIndex + 1 }} / {{ $totalQuestions }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Answered:</span>
                                <span class="font-medium">{{ $this->answeredCount }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Remaining:</span>
                                <span class="font-medium">{{ $totalQuestions - ($currentQuestionIndex + 1) }}</span>
                            </div>
                        </div>

                        <!-- Visual Progress -->
                        <div class="mb-6">
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                <div class="bg-indigo-600 h-3 rounded-full transition-all duration-300" style="width: {{ $this->progressPercentage }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 text-center">{{ number_format($this->progressPercentage, 1) }}% Complete</p>
                        </div>

                        <!-- Scoring Info -->
                        <div class="text-xs text-gray-500">
                            <p class="font-medium mb-2">Scoring:</p>
                            <p>✓ Correct: +1 point</p>
                            <p>✗ Incorrect: -0.25 points</p>
                            <p>○ Unanswered: 0 points</p>
                        </div>

                        <!-- Important Note -->
                        <div class="mt-6 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-xs text-yellow-800">
                                <strong>Note:</strong> You can navigate between questions using the Previous and Next buttons.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Question Area -->
                <div class="lg:col-span-3">
                    @if($currentQuestion)
                        <div class="bg-white rounded-lg shadow">
                            <!-- Question -->
                            <div class="p-6 border-b border-gray-200">
                                <div class="flex items-start justify-between mb-4">
                                    <h2 class="text-lg font-medium text-gray-900">
                                        Question {{ $currentQuestionIndex + 1 }}
                                    </h2>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ ucfirst($currentQuestion->type) }}
                                    </span>
                                </div>
                                <p class="text-gray-700 text-lg leading-relaxed">{{ $currentQuestion->question_text }}</p>
                            </div>

                            <!-- Answer Options -->
                            <div class="p-6">
                                @php
                                    $options = $currentQuestion->options; // Uses the getOptionsAttribute() method
                                @endphp
                                
                                @if(count($options) > 0)
                                    <div class="space-y-3" wire:key="question-{{ $currentQuestionIndex }}">
                                        @foreach($options as $optionNumber => $optionText)
                                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-all duration-200 {{ $selectedAnswer === $optionText ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                                                <input
                                                    type="radio"
                                                    name="question_{{ $currentQuestionIndex }}"
                                                    wire:model.live="selectedAnswer"
                                                    value="{{ $optionText }}"
                                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300"
                                                >
                                                <span class="ml-3 text-gray-700 font-medium">{{ chr(64 + $optionNumber) }}. {{ $optionText }}</span>
                                                @if($selectedAnswer === $optionText)
                                                    <svg class="w-5 h-5 text-indigo-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <!-- Text Answer for questions without options -->
                                    <div>
                                        <textarea
                                            wire:model.live="selectedAnswer"
                                            rows="4"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="Enter your answer..."
                                        ></textarea>
                                        <button 
                                            wire:click="selectAnswerFromTextarea"
                                            class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                        >
                                            Submit Answer
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <!-- Navigation -->
                            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    @if($selectedAnswer)
                                        Answer selected. 
                                        @if($currentQuestionIndex < $totalQuestions - 1)
                                            Click "Next Question" to continue.
                                        @else
                                            Click "Submit Test" to finish.
                                        @endif
                                    @else
                                        Select an answer or skip this question.
                                    @endif
                                </div>

                                <div class="flex space-x-3">
                                    <!-- Previous Question Button -->
                                    @if($currentQuestionIndex > 0)
                                        <button
                                            wire:click="previousQuestion"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            Previous Question
                                        </button>
                                    @endif

                                    @if($currentQuestionIndex === $totalQuestions - 1)
                                        <button
                                            wire:click="submitTest"
                                            wire:confirm="Are you sure you want to submit the test? This action cannot be undone."
                                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Submit Test
                                        </button>
                                    @else
                                        @if($selectedAnswer)
                                            <button
                                                wire:click="nextQuestion"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                            >
                                                Next Question
                                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                        @endif
                                        
                                        <button
                                            wire:click="skipQuestion"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                                        >
                                            Skip Question
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow p-6 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No question available</h3>
                                <p class="mt-1 text-sm text-gray-500">There was an error loading the question.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif 