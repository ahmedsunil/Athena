<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeTestimonial extends Model
{
    protected $fillable = [
        'photo_path', 'name', 'previous_designation',
        'current_designation', 'message', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
