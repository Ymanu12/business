<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['category_id', 'rate', 'label', 'is_active'])]
class Commission extends Model
{
    protected function casts(): array
    {
        return ['rate' => 'float', 'is_active' => 'boolean'];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function percentage(): string
    {
        return ($this->rate * 100) . '%';
    }
}
