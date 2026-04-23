<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'gig_extra_id', 'title', 'price', 'quantity'])]
class OrderExtra extends Model
{
    protected function casts(): array
    {
        return ['price' => 'float'];
    }

    public function order(): BelongsTo    { return $this->belongsTo(Order::class); }
    public function gigExtra(): BelongsTo { return $this->belongsTo(GigExtra::class); }

    public function subtotal(): float
    {
        return $this->price * $this->quantity;
    }
}
