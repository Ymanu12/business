<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'requested_by', 'reason', 'attachments'])]
class OrderRevision extends Model
{
    protected function casts(): array
    {
        return ['attachments' => 'array'];
    }

    public function order(): BelongsTo      { return $this->belongsTo(Order::class); }
    public function requester(): BelongsTo  { return $this->belongsTo(User::class, 'requested_by'); }
}
