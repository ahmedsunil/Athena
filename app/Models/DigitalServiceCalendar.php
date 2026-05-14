<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DigitalServiceCalendar extends Model
{
    use HasFactory;

    protected $fillable = ['sort_order', 'title', 'year', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(DigitalServiceCalendarEntry::class, 'calendar_id');
    }
}
