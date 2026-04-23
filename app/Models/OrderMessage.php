<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'sender_id', 'message', 'attachments', 'is_system', 'read_at'])]
class OrderMessage extends Model
{
    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'is_system'   => 'boolean',
            'read_at'     => 'datetime',
        ];
    }

    public function order(): BelongsTo  { return $this->belongsTo(Order::class); }
    public function sender(): BelongsTo { return $this->belongsTo(User::class, 'sender_id'); }
}
