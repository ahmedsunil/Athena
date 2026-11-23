<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WebApp extends Model
{
    protected $fillable = [
        'sort_order',
        'title',
        'subtitle',
        'icon_key',
        'icon_path',
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

    protected function iconUrl(): Attribute
    {
        return Attribute::get(fn () => $this->icon_path ? Storage::disk('public')->url($this->icon_path) : null);
    }
}
