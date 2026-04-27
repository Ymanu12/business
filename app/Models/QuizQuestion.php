<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

#[Fillable(['quiz_id', 'question', 'type', 'correct_answer', 'position'])]
class QuizQuestion extends Model
{
    protected function casts(): array
    {
        return ['position' => 'integer'];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuizChoice::class, 'question_id')->orderBy('position');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'question_id');
    }
}
