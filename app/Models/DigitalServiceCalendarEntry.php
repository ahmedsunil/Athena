<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalServiceCalendarEntry extends Model
{
    use HasFactory;
    protected $fillable = [
        'sort_order', 'title', 'date', 'end_date', 'type', 'description', 'is_active',
    ];

    protected $casts = [
        'date'      => 'date',
        'end_date'  => 'date',
        'is_active' => 'boolean',
    ];

    public function getTypeBadgeClassesAttribute(): string
    {
        return match($this->type) {
            'term'    => 'bg-rose-100 text-rose-700',
            'holiday' => 'bg-emerald-100 text-emerald-700',
            'exam'    => 'bg-amber-100 text-amber-700',
            'event'   => 'bg-sky-100 text-sky-700',
            default   => 'bg-slate-100 text-slate-600',
        };
    }
}
