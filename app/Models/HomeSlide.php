<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeSlide extends Model
{
    protected $fillable = [
        'title', 'description', 'image_path',
        'button_1_label', 'button_1_link_key',
        'button_2_label', 'button_2_link_key',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public static function resolveImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::url($path);
    }

    public function getImageUrlAttribute(): ?string
    {
        return self::resolveImageUrl($this->image_path);
    }
}
