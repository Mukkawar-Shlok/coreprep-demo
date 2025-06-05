<?php

namespace App\Livewire;

use App\Models\Test;
use App\Models\Question;
use App\Models\TestSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class TakeTest extends Component {
    public Test $test;
    public array $questionIds = [];
    public int $currentQuestionIndex = 0;
    public $currentQuestion;
    public array $answers = [];
    public $selectedAnswer = null;
    public bool $testCompleted = false;
    public int $totalQuestions = 0;

    public function mount($testId) {
        $this->test = Test::findOrFail($testId);

        // Check if user has already taken this test
        $existingSubmission = TestSubmission::where('user_id', Auth::id())
            ->where('test_id', $this->test->id)
            ->first();

        if ($existingSubmission) {
            session()->flash('error', 'You have already taken this test. You can only attempt each test once.');
            return $this->redirect('/dashboard');
        }

        // Get question IDs from the test's questions relationship
        $this->questionIds = $this->test->questions()->pluck('id')->toArray();
        $this->totalQuestions = count($this->questionIds);

        if (empty($this->questionIds)) {
            session()->flash('error', 'This test has no questions.');
            return $this->redirect('/dashboard');
        }

        $this->loadCurrentQuestion();
    }

    public function loadCurrentQuestion() {
        if (isset($this->questionIds[$this->currentQuestionIndex])) {
            $questionId = $this->questionIds[$this->currentQuestionIndex];
            $this->currentQuestion = Question::find($questionId);

            if ($this->currentQuestion) {
                // Add index to question for easier reference
                $this->currentQuestion->index = $this->currentQuestionIndex;

                // Restore previously selected answer if it exists
                if (isset($this->answers[$this->currentQuestionIndex])) {
                    $this->selectedAnswer = $this->answers[$this->currentQuestionIndex];
                } else {
                    $this->selectedAnswer = null;
                }
            }
        } else {
            // Reset if no question found
            $this->currentQuestion = null;
            $this->selectedAnswer = null;
        }
    }

    public function selectAnswer($answer) {
        if (!$this->currentQuestion) {
            return;
        }

        $this->selectedAnswer = $answer;
        $this->answers[$this->currentQuestionIndex] = $answer;
    }

    public function updatedSelectedAnswer($value) {
        // This method is automatically called when selectedAnswer changes via wire:model
        if ($this->currentQuestion && $value !== null) {
            $this->answers[$this->currentQuestionIndex] = $value;
        } elseif ($value === null && isset($this->answers[$this->currentQuestionIndex])) {
            // If selection is cleared, remove from answers array
            unset($this->answers[$this->currentQuestionIndex]);
        }
    }

    public function selectAnswerFromTextarea() {
        if (!empty($this->selectedAnswer) && $this->currentQuestion) {
            $this->answers[$this->currentQuestionIndex] = $this->selectedAnswer;
        }
    }

    public function nextQuestion() {
        // Save current answer if selected
        if ($this->selectedAnswer !== null && $this->currentQuestion) {
            $this->answers[$this->currentQuestionIndex] = $this->selectedAnswer;
        }

        // Move to next question
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->currentQuestionIndex++;
            $this->loadCurrentQuestion();
        }
    }

    public function previousQuestion() {
        // Save current answer if selected
        if ($this->selectedAnswer !== null && $this->currentQuestion) {
            $this->answers[$this->currentQuestionIndex] = $this->selectedAnswer;
        }

        // Move to previous question
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->loadCurrentQuestion();
        }
    }

    public function skipQuestion() {
        // Clear any selected answer for this question
        $this->selectedAnswer = null;

        // Remove any previously stored answer for this question
        if (isset($this->answers[$this->currentQuestionIndex])) {
            unset($this->answers[$this->currentQuestionIndex]);
        }

        // Move to next question
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->currentQuestionIndex++;
            $this->loadCurrentQuestion();
        } else {
            $this->submitTest();
        }
    }

    private function resetSelection() {
        $this->selectedAnswer = null;
        $this->dispatch('$refresh');
    }

    public function exitTest() {
        return $this->redirect('/dashboard');
    }

    public function backToDashboard() {
        return $this->redirect('/dashboard');
    }

    public function submitTest() {
        try {
            // Calculate detailed score breakdown
            $scoreDetails = $this->calculateScoreDetails();

            // Determine pass/fail using weighted score
            $maxPossibleScore = $this->totalQuestions;
            $weightedPercentage = $maxPossibleScore > 0 ? ($scoreDetails['score'] / $maxPossibleScore) * 100 : 0;
            $result = $weightedPercentage >= 60 ? 'pass' : 'fail';

            // Save submission to database
            $submission = TestSubmission::create([
                'user_id' => Auth::id(),
                'test_id' => $this->test->id,
                'correct_ans_count' => $scoreDetails['correct'],
                'wrong_ans_count' => $scoreDetails['incorrect'],
                'result' => $result,
            ]);

            $this->testCompleted = true;

            // Create a user-friendly success message
            $scoreText = "Score: {$scoreDetails['score']}/{$maxPossibleScore} ({$weightedPercentage}%)";
            $detailsText = "{$scoreDetails['correct']} correct, {$scoreDetails['incorrect']} wrong, {$scoreDetails['unanswered']} unanswered";
            $resultText = "Result: " . ucfirst($result);

            session()->flash('success', "Test completed! {$scoreText}. {$detailsText}. {$resultText}");
        } catch (\Exception $e) {
            Log::error('Error submitting test: ' . $e->getMessage());
            session()->flash('error', 'There was an error submitting your test. Please try again.');
        }
    }

    private function calculateScoreDetails() {
        $correct = 0;
        $incorrect = 0;
        $unanswered = 0;
        $score = 0;

        foreach ($this->questionIds as $index => $questionId) {
            $question = Question::find($questionId);
            $userAnswer = $this->answers[$index] ?? null;

            if ($userAnswer === null) {
                $unanswered++;
                // No penalty for unanswered questions
            } elseif ($this->isAnswerCorrect($question, $userAnswer)) {
                $correct++;
                $score += 1; // +1 for correct answer
            } else {
                $incorrect++;
                $score -= 0.25; // -0.25 for incorrect answer
            }
        }

        return [
            'correct' => $correct,
            'incorrect' => $incorrect,
            'unanswered' => $unanswered,
            'total' => $this->totalQuestions,
            'score' => round($score, 2),
        ];
    }

    private function isAnswerCorrect($question, $userAnswer): bool {
        if (!$question) {
            return false;
        }

        if ($question->type === 'text') {
            // For text questions without predefined options, we can't really validate
            // since there's no correct_answer_text field. For now, return false
            // or you could implement custom logic here
            return false;
        } else {
            // For multiple choice/true-false, compare option text
            // The user answer should be the option text they selected
            $correctOptionText = $question->correct_answer; // This uses getCorrectAnswerAttribute()
            return $userAnswer === $correctOptionText;
        }
    }

    public function getProgressPercentageProperty() {
        return $this->totalQuestions > 0 ? (($this->currentQuestionIndex + 1) / $this->totalQuestions) * 100 : 0;
    }

    public function getAnsweredCountProperty() {
        return count(array_filter($this->answers));
    }

    public function getCurrentQuestionOptions() {
        if (!$this->currentQuestion) {
            return [];
        }

        return $this->currentQuestion->options;
    }

    public function getCurrentQuestionNumber() {
        return $this->currentQuestionIndex + 1;
    }

    #[Layout('components.layouts.app')]
    #[Title('Take Test')]
    public function render() {
        return view('livewire.take-test', [
            'answers' => $this->answers
        ]);
    }
}
