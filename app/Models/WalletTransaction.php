<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'wallet_id', 'type', 'amount', 'balance_before', 'balance_after',
    'reference', 'description', 'metadata', 'transactable_type', 'transactable_id',
])]
class WalletTransaction extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'amount'         => 'float',
            'balance_before' => 'float',
            'balance_after'  => 'float',
            'metadata'       => 'array',
            'created_at'     => 'datetime',
        ];
    }

    public function wallet(): BelongsTo { return $this->belongsTo(Wallet::class); }

    public function transactable(): MorphTo { return $this->morphTo(); }

    public function isCredit(): bool
    {
        return in_array($this->type, ['credit', 'released']);
    }
}
