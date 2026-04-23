<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['gig_id', 'title', 'description', 'price', 'delivery_days', 'is_active', 'sort_order'])]
class GigExtra extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'price' => 'float'];
    }

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }
}
