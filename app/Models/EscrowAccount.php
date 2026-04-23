<?php

namespace App\Models;

use App\Enums\EscrowStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id', 'amount', 'platform_fee', 'freelancer_amount',
    'status', 'held_at', 'released_at',
])]
class EscrowAccount extends Model
{
    protected function casts(): array
    {
        return [
            'status'       => EscrowStatus::class,
            'held_at'      => 'datetime',
            'released_at'  => 'datetime',
            'amount'       => 'float',
            'platform_fee' => 'float',
            'freelancer_amount' => 'float',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isHeld(): bool
    {
        return $this->status === EscrowStatus::Held;
    }
}
