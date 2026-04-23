<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id', 'gig_id', 'reviewer_id', 'reviewed_id',
    'rating', 'communication_rating', 'service_rating',
    'recommend', 'comment', 'reply', 'reply_at', 'is_verified', 'is_hidden',
])]
class Review extends Model
{
    protected function casts(): array
    {
        return [
            'recommend'   => 'boolean',
            'is_verified' => 'boolean',
            'is_hidden'   => 'boolean',
            'reply_at'    => 'datetime',
            'rating'      => 'integer',
        ];
    }

    public function order(): BelongsTo    { return $this->belongsTo(Order::class); }
    public function gig(): BelongsTo      { return $this->belongsTo(Gig::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewer_id'); }
    public function reviewed(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_id'); }

    public function stars(): array
    {
        return array_fill(0, $this->rating, true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false)->where('is_verified', true);
    }
}
