<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['gig_id', 'type', 'path', 'thumbnail', 'caption', 'sort_order'])]
class GigGallery extends Model
{
    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }
}
