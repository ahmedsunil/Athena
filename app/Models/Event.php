<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'public_id', 'status', 'title', 'slug',
        'date_start', 'date_end', 'location',
        'cover_image_path', 'short_description', 'full_description',
        'attachments', 'contact',
        'is_featured', 'featured_sort_order', 'is_active',
    ];

    protected $casts = [
        'date_start'   => 'date',
        'date_end'     => 'date',
        'attachments'  => 'array',
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
    ];

    public static function resolveCoverImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return Storage::url($path);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return self::resolveCoverImageUrl($this->cover_image_path);
    }

    public function getFormattedDateRangeAttribute(): string
    {
        if (! $this->date_start) {
            return '';
        }
        $start = $this->date_start->format('j M Y');
        if (! $this->date_end || $this->date_end->eq($this->date_start)) {
            return $start;
        }
        return $start . ' – ' . $this->date_end->format('j M Y');
    }
}
