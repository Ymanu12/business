<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'gig_id'])]
class Favorite extends Model
{
    public $timestamps = false;

    const CREATED_AT = 'created_at';

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function gig(): BelongsTo  { return $this->belongsTo(Gig::class); }
}
