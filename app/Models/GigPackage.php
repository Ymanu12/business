<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'gig_id', 'type', 'name', 'description', 'price',
    'delivery_days', 'revision_count', 'features', 'is_active',
])]
class GigPackage extends Model
{
    protected function casts(): array
    {
        return [
            'features'  => 'array',
            'is_active' => 'boolean',
            'price'     => 'float',
        ];
    }

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'basic'    => 'Basique',
            'standard' => 'Standard',
            'premium'  => 'Premium',
            default    => $this->type,
        };
    }

    public function formattedPrice(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ' . ($this->gig->currency ?? 'XOF');
    }
}
