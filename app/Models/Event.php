<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public $guarded = [];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'attachments' => 'array',
        'contact' => 'array',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Event $event): void {
            if (blank($event->slug) && filled($event->title)) {
                $event->slug = str($event->title)->slug()->toString();
            }
        });
    }
}
