<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Spatie\Translatable\HasTranslations;
class DigitalServiceCalendar extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'description'];

    use HasFactory;

    protected $fillable = [
        'sort_order', 'title', 'year', 'description', 'is_active',
        'stat_teaching_days', 'stat_exam_days', 'stat_report_prep_days',
        'stat_teacher_pd_days', 'stat_non_teaching_days', 'stat_total_days',
        'term1_dates', 'term1_total_days', 'term1_teaching_days', 'term1_exam_days',
        'term2_dates', 'term2_total_days', 'term2_teaching_days', 'term2_exam_days',
        'exam_series',
    ];

    protected $casts = [
        'is_active'              => 'boolean',
        'stat_teaching_days'     => 'decimal:1',
        'stat_exam_days'         => 'decimal:1',
        'stat_report_prep_days'  => 'decimal:1',
        'stat_teacher_pd_days'   => 'decimal:1',
        'stat_non_teaching_days' => 'decimal:1',
        'stat_total_days'        => 'decimal:1',
        'term1_total_days'       => 'decimal:1',
        'term1_teaching_days'    => 'decimal:1',
        'term1_exam_days'        => 'decimal:1',
        'term2_total_days'       => 'decimal:1',
        'term2_teaching_days'    => 'decimal:1',
        'term2_exam_days'        => 'decimal:1',
        'exam_series'            => 'array',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(DigitalServiceCalendarEntry::class, 'calendar_id');
    }
}
