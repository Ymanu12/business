<?php

namespace App\Models;

use App\Enums\DisputeStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

#[Fillable([
    'order_id', 'opened_by', 'reason', 'description', 'status',
    'resolved_by', 'resolution_note', 'client_refund', 'freelancer_payout', 'resolved_at',
])]
class Dispute extends Model
{
    protected function casts(): array
    {
        return [
            'status'      => DisputeStatus::class,
            'resolved_at' => 'datetime',
            'client_refund'      => 'float',
            'freelancer_payout'  => 'float',
        ];
    }

    public function order(): BelongsTo    { return $this->belongsTo(Order::class); }
    public function opener(): BelongsTo   { return $this->belongsTo(User::class, 'opened_by'); }
    public function resolver(): BelongsTo { return $this->belongsTo(User::class, 'resolved_by'); }

    public function disputeMessages(): HasMany
    {
        return $this->hasMany(DisputeMessage::class)->oldest();
    }

    public function isOpen(): bool
    {
        return $this->status === DisputeStatus::Open;
    }
}
