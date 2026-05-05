<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSlide extends Model
{
    protected $fillable = [
        'title', 'description', 'image_path',
        'button_1_label', 'button_1_link_key',
        'button_2_label', 'button_2_link_key',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
