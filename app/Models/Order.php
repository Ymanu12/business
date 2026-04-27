<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Support\Str;

#[Fillable([
    'uuid', 'gig_id', 'package_id', 'client_id', 'freelancer_id',
    'title', 'requirements', 'price', 'currency', 'platform_fee',
    'freelancer_amount', 'delivery_days', 'revisions_allowed',
    'revisions_used', 'status', 'due_date', 'auto_complete_at',
    'delivered_at', 'completed_at', 'cancelled_at',
    'cancellation_reason', 'cancelled_by',
])]
class Order extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status'          => OrderStatus::class,
            'due_date'        => 'datetime',
            'auto_complete_at'=> 'datetime',
            'delivered_at'    => 'datetime',
            'completed_at'    => 'datetime',
            'cancelled_at'    => 'datetime',
            'price'           => 'float',
            'platform_fee'    => 'float',
            'freelancer_amount'=> 'float',
        ];
    }

    protected static function booted(): void
    {
        static::creating(fn (Order $order) => $order->uuid ??= (string) Str::uuid());
    }

    // ── Relations ─────────────────────────────────────────────────

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(GigPackage::class);
    }

    public function extras(): HasMany
    {
        return $this->hasMany(OrderExtra::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(OrderMessage::class)->latest();
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(OrderDelivery::class)->latest();
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(OrderRevision::class)->latest();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function escrow(): HasOne
    {
        return $this->hasOne(EscrowAccount::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }

    public function clientEvaluation(): HasOne
    {
        return $this->hasOne(ClientEvaluation::class);
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            'paid', 'in_progress', 'delivered', 'revision_requested',
        ]);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ── Helpers ───────────────────────────────────────────────────

    public function isOwnedBy(User $user): bool
    {
        return in_array($user->id, [$this->client_id, $this->freelancer_id]);
    }

    public function hasRevisionsLeft(): bool
    {
        return $this->revisions_used < $this->revisions_allowed;
    }

    public function formattedPrice(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' ' . $this->currency;
    }

    public function daysLeft(): ?int
    {
        if (! $this->due_date) return null;

        return max(0, (int) now()->diffInDays($this->due_date, false));
    }
}
