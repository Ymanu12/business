<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['dispute_id', 'sender_id', 'message', 'attachments'])]
class DisputeMessage extends Model
{
    protected function casts(): array
    {
        return ['attachments' => 'array'];
    }

    public function dispute(): BelongsTo { return $this->belongsTo(Dispute::class); }
    public function sender(): BelongsTo  { return $this->belongsTo(User::class, 'sender_id'); }
}
