<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Specify which fields are mass assignable
    protected $fillable = [
        'title',
        'excerpt',
        'content',
        'featured_image',
        'status',
        'published_at',
    ];

    // Optional: cast fields to proper types
    protected $casts = [
        'status' => 'boolean',
        'published_at' => 'datetime',
    ];
}
