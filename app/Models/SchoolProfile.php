<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Spatie\Translatable\HasTranslations;
class SchoolProfile extends Model
{
    use HasTranslations;

    public array $translatable = ['school_name', 'motto', 'short_description', 'address', 'island', 'atoll', 'country', 'principal_name', 'principal_designation', 'principal_message'];

    protected $fillable = [
        'school_name', 'motto', 'short_description', 'logo_path',
        'email', 'phone', 'address', 'island', 'atoll', 'country',
        'principal_name', 'principal_designation', 'principal_message', 'principal_photo_path',
    ];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }

    public static function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        return Storage::url($path);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return self::resolveMediaUrl($this->logo_path);
    }

    public function getPrincipalPhotoUrlAttribute(): ?string
    {
        return self::resolveMediaUrl($this->principal_photo_path);
    }
}
