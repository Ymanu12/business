<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    protected $fillable = [
        'title',
        'audio_file', 
        'etat',
        'description',
        'cover_image',
        'release_date',
        'is_active',
    ];
}
