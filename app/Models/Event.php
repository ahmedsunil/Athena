<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Spatie\Translatable\HasTranslations;
class Event extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'short_description', 'full_description', 'location'];

    protected $fillable = [
        'public_id', 'status', 'title', 'slug',
        'date_start', 'time_start', 'date_end', 'time_end', 'location',
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

        $startDate = $this->date_start->format('j M Y');
        $endDate = $this->date_end?->format('j M Y');
        $startTime = $this->formatTime($this->time_start);
        $endTime = $this->formatTime($this->time_end);

        if (! $this->date_end || $this->date_end->eq($this->date_start)) {
            if ($startTime && $endTime) {
                return "{$startDate}, {$startTime} - {$endTime}";
            }

            if ($startTime || $endTime) {
                return "{$startDate}, " . ($startTime ?: $endTime);
            }

            return $startDate;
        }

        $start = $startTime ? "{$startDate}, {$startTime}" : $startDate;
        $end = $endTime ? "{$endDate}, {$endTime}" : $endDate;

        return "{$start} - {$end}";
    }

    private function formatTime(?string $time): ?string
    {
        if (! $time) {
            return null;
        }

        $time = substr($time, 0, 8);

        return Carbon::createFromFormat(strlen($time) === 5 ? 'H:i' : 'H:i:s', $time)->format('g:i A');
    }
}
