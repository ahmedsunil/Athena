<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeTestimonial extends Model
{
    protected $fillable = [
        'photo_path', 'name', 'previous_designation',
        'current_designation', 'message', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public static function resolvePhotoUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::url($path);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePhotoUrl($this->photo_path);
    }
}
