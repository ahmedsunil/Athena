<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Spatie\Translatable\HasTranslations;
class StaffMember extends Model
{
    use HasTranslations;

    public array $translatable = ['designation', 'education'];

    protected $fillable = [
        'name', 'name_dv', 'designation', 'education', 'photo_path',
        'section', 'sub_section', 'work_experiences',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'work_experiences' => 'array',
        'is_active'        => 'boolean',
    ];

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
