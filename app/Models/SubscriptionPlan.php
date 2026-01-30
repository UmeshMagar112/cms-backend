<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name', 'duration_value', 'duration_unit', 'price', 
        'currency', 'published', 'sort_order', 'features'
    ];

    protected $casts = [
        'published' => 'boolean',
        'features'  => 'array', 
    ];
}
