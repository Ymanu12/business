<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Live extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_time',
        'platform',
        'url',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'is_active' => 'boolean',
    ];
}

