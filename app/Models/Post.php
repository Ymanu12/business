<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'author',
        'published_at',
        'views',
        'image',
        'thumbnail'
    ];

    protected $dates = ['published_at'];
    
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $casts = [
        'published_at' => 'datetime',
    ];    

    // Dans app/Models/Post.php
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}