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
}
