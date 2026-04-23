<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

#[Fillable(['reporter_id', 'reportable_type', 'reportable_id', 'reason', 'description', 'status', 'reviewed_by'])]
class Report extends Model
{
    public function reporter(): BelongsTo { return $this->belongsTo(User::class, 'reporter_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function reportable(): MorphTo { return $this->morphTo(); }
}
