<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

#[Fillable(['gig_id', 'title', 'description', 'video_path', 'file_path', 'position', 'is_preview'])]
class GigLesson extends Model
{
    protected function casts(): array
    {
        return [
            'is_preview' => 'boolean',
            'position'   => 'integer',
        ];
    }

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    public function isCompletedBy(int $userId): bool
    {
        return $this->progresses()->where('user_id', $userId)->whereNotNull('completed_at')->exists();
    }
}
