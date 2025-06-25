<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'video_file',
        'poster_image',
        'duration',
        'upload_date',
        'description',
        'etat',
        'attachment',
        'type',
    ];

    protected $casts = [
        'duration' => 'integer',
    ];
}
