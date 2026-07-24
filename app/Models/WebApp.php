<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebApp extends Model
{
    protected $fillable = [
        'sort_order',
        'title',
        'subtitle',
        'icon_key',
        'url',
        'action_label',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
