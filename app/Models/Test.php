<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Test extends Model {
    protected $fillable = [
        'title',
        'description',
        'is_active',
        'questions',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'questions' => 'array',
    ];

    /**
     * Get all questions for this test (legacy support)
     */
    public function questions(): HasMany {
        return $this->hasMany(Question::class);
    }

    /**
     * Get all submissions for this test
     */
    public function submissions(): HasMany {
        return $this->hasMany(TestSubmission::class);
    }

    /**
     * Get the total number of questions in this test
     */
    public function getTotalQuestionsAttribute(): int {
        // Check if questions are stored as array first
        if (is_array($this->questions)) {
            return count($this->questions);
        }

        // Fallback to relationship count for legacy support
        return $this->questions()->count();
    }

    /**
     * Get questions as array (supports both new array format and legacy relationship)
     */
    public function getQuestionsArrayAttribute(): array {
        if (is_array($this->questions)) {
            return $this->questions;
        }

        // Convert legacy questions to array format
        return $this->questions()->get()->map(function ($question) {
            return [
                'id' => $question->id,
                'question_text' => $question->question_text,
                'type' => $question->type,
                'option_1' => $question->option_1,
                'option_2' => $question->option_2,
                'option_3' => $question->option_3,
                'option_4' => $question->option_4,
                'option_5' => $question->option_5,
                'correct_option' => $question->correct_option,
                'correct_answer_text' => null,
            ];
        })->toArray();
    }

    /**
     * Get a specific question by index
     */
    public function getQuestionByIndex(int $index): ?array {
        $questions = $this->getQuestionsArrayAttribute();
        return $questions[$index] ?? null;
    }

    /**
     * Check if user answer is correct for a specific question
     */
    public function isAnswerCorrect(int $questionIndex, $userAnswer): bool {
        $question = $this->getQuestionByIndex($questionIndex);

        if (!$question) {
            return false;
        }

        if ($question['type'] === 'text') {
            // For text questions, compare with expected answer
            $expectedAnswer = $question['correct_answer_text'] ?? '';
            return strtolower(trim($userAnswer)) === strtolower(trim($expectedAnswer));
        } else {
            // For multiple choice/true-false, compare option numbers
            return (int) $userAnswer === (int) $question['correct_option'];
        }
    }

    /**
     * Get the correct answer for a specific question
     */
    public function getCorrectAnswer(int $questionIndex): ?string {
        $question = $this->getQuestionByIndex($questionIndex);

        if (!$question) {
            return null;
        }

        if ($question['type'] === 'text') {
            return $question['correct_answer_text'];
        } elseif (isset($question['correct_option']) && $question['correct_option'] >= 1 && $question['correct_option'] <= 5) {
            return $question["option_{$question['correct_option']}"] ?? null;
        }

        return null;
    }

    /**
     * Scope for active tests
     */
    public function scopeActive($query) {
        // Check if is_active column exists before using it
        if (Schema::hasColumn('tests', 'is_active')) {
            return $query->where('is_active', true);
        }

        // If column doesn't exist, return all tests
        return $query;
    }
}
