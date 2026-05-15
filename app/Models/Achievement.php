<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Spatie\Translatable\HasTranslations;
class Achievement extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'description', 'award', 'event_name'];

    protected $fillable = [
        'title', 'category', 'year', 'description',
        'award', 'event_name', 'person_name', 'photo_path',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean', 'year' => 'integer'];

    public static function resolvePhotoUrl(?string $path): ?string
    {
        if (! $path) return null;
        if (Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePhotoUrl($this->photo_path);
    }
}
