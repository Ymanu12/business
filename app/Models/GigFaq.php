<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['gig_id', 'question', 'answer', 'sort_order'])]
class GigFaq extends Model
{
    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }
}
