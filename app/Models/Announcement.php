<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{
    protected $fillable = [
        'sort_order',
        'icon_key',
        'category',
        'title',
        'slug',
        'description',
        'deadline',
        'attachments',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'attachments' => 'array',
        'is_active' => 'boolean',
    ];

    public function getFormattedDeadlineAttribute(): ?string
    {
        return $this->deadline?->format('j M Y');
    }

    public function getAttachmentLinksAttribute(): array
    {
        return collect($this->attachments ?? [])
            ->map(function (array $attachment): array {
                return [
                    'label' => $attachment['label'] ?? $attachment['name'] ?? 'Attachment',
                    'url' => isset($attachment['path']) ? Storage::url($attachment['path']) : ($attachment['url'] ?? '#'),
                    'size' => $attachment['size'] ?? null,
                ];
            })
            ->values()
            ->all();
    }
}
