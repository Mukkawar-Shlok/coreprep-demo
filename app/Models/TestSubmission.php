<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestSubmission extends Model {
    protected $fillable = [
        'test_id',
        'user_id',
        'correct_ans_count',
        'wrong_ans_count',
        'result',
    ];

    protected $casts = [
        'correct_ans_count' => 'integer',
        'wrong_ans_count' => 'integer',
        'result' => 'string',
    ];

    public function test(): BelongsTo {
        return $this->belongsTo(Test::class);
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    // Calculate total attempted questions
    public function getTotalAttemptedAttribute() {
        return $this->correct_ans_count + $this->wrong_ans_count;
    }

    // Calculate accuracy percentage
    public function getAccuracyAttribute() {
        $totalAttempted = $this->getTotalAttemptedAttribute();
        return $totalAttempted > 0 ? ($this->correct_ans_count / $totalAttempted) * 100 : 0;
    }

    // Get pass/fail status as boolean
    public function getIsPassAttribute() {
        return $this->result === 'pass';
    }
}
