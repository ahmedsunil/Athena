<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DigitalServiceCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'sort_order', 'title', 'year', 'description', 'is_active',
        'stat_teaching_days', 'stat_exam_days', 'stat_report_prep_days',
        'stat_teacher_pd_days', 'stat_non_teaching_days', 'stat_total_days',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'stat_teaching_days'   => 'decimal:1',
        'stat_exam_days'       => 'decimal:1',
        'stat_report_prep_days'=> 'decimal:1',
        'stat_teacher_pd_days' => 'decimal:1',
        'stat_non_teaching_days'=> 'decimal:1',
        'stat_total_days'      => 'decimal:1',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(DigitalServiceCalendarEntry::class, 'calendar_id');
    }
}
