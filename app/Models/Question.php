<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model {
    protected $fillable = [
        'test_id',
        'question_text',
        'option_1',
        'option_2',
        'option_3',
        'option_4',
        'option_5',
        'correct_option',
        'type',
    ];

    protected $casts = [
        'correct_option' => 'integer',
    ];

    /**
     * Get the test that owns this question
     */
    public function test(): BelongsTo {
        return $this->belongsTo(Test::class);
    }

    /**
     * Get all options as an array
     */
    public function getOptionsAttribute(): array {
        $options = [];
        for ($i = 1; $i <= 5; $i++) {
            $option = $this->{"option_$i"};
            if ($option) {
                $options[$i] = $option;
            }
        }
        return $options;
    }

    /**
     * Get the question type (determine automatically if not set)
     */
    public function getTypeAttribute(): string {
        // If type is explicitly set, use it
        if (isset($this->attributes['type']) && $this->attributes['type']) {
            return $this->attributes['type'];
        }

        // Auto-determine type based on options
        $optionCount = count($this->options);

        if ($optionCount === 0) {
            return 'text';
        } elseif ($optionCount === 2) {
            // Check if it's True/False
            $options = array_values($this->options);
            if (strtolower($options[0]) === 'true' && strtolower($options[1]) === 'false') {
                return 'true_false';
            }
        }

        return 'multiple_choice';
    }

    /**
     * Get the correct answer text
     */
    public function getCorrectAnswerAttribute(): ?string {
        if ($this->correct_option && $this->correct_option >= 1 && $this->correct_option <= 5) {
            return $this->{"option_{$this->correct_option}"};
        }
        return null;
    }

    /**
     * Check if a given option number is correct
     */
    public function isCorrectOption(int $optionNumber): bool {
        return $this->correct_option === $optionNumber;
    }

    /**
     * Scope for questions belonging to a specific test
     */
    public function scopeForTest($query, $testId) {
        return $query->where('test_id', $testId);
    }
}
