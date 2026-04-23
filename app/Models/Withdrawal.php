<?php

namespace App\Models;

use App\Enums\WithdrawalStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'amount', 'currency', 'method', 'account_details',
    'status', 'transaction_ref', 'rejection_reason', 'processed_by', 'processed_at',
])]
class Withdrawal extends Model
{
    protected function casts(): array
    {
        return [
            'status'          => WithdrawalStatus::class,
            'account_details' => 'array',
            'processed_at'    => 'datetime',
            'amount'          => 'float',
        ];
    }

    public function user(): BelongsTo        { return $this->belongsTo(User::class); }
    public function processor(): BelongsTo   { return $this->belongsTo(User::class, 'processed_by'); }

    public function formattedAmount(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' ' . $this->currency;
    }
}
