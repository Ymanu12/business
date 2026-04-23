<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

#[Fillable(['user_id', 'balance', 'pending_balance', 'currency'])]
class Wallet extends Model
{
    protected function casts(): array
    {
        return [
            'balance'         => 'float',
            'pending_balance' => 'float',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class)->latest('created_at');
    }

    public function availableBalance(): float
    {
        return max(0, $this->balance);
    }

    public function formattedBalance(): string
    {
        return number_format($this->balance, 0, ',', ' ') . ' ' . $this->currency;
    }
}
