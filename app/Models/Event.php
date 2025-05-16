<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'event_date',
        'event_time', // <--- AJOUT ICI
        'location',
        'image_url',
        'etat',
        'description',
    ];
}
