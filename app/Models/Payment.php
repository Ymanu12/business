<?php

namespace App\Models;

use App\Enums\{PaymentMethod, PaymentStatus};
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id', 'user_id', 'amount', 'currency', 'method',
    'provider', 'provider_id', 'provider_ref', 'status', 'paid_at', 'metadata',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'method'   => PaymentMethod::class,
            'status'   => PaymentStatus::class,
            'paid_at'  => 'datetime',
            'metadata' => 'array',
            'amount'   => 'float',
        ];
    }

    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function user(): BelongsTo  { return $this->belongsTo(User::class); }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::Paid;
    }
}
