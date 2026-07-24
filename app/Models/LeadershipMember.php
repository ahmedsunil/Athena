<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Spatie\Translatable\HasTranslations;
class LeadershipMember extends Model
{
    use HasTranslations;

    public array $translatable = ['role', 'bio'];

    protected $fillable = [
        'name', 'role', 'bio', 'photo_path', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

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
