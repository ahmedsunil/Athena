<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use Spatie\Translatable\HasTranslations;
class AcademicLevel extends Model
{
    use HasTranslations;

    public array $translatable = ['label', 'age_range', 'year_groups'];

    protected $fillable = [
        'sort_order', 'abbreviation', 'label', 'age_range', 'year_groups',
        'lead_teacher', 'lead_teacher_photo_path',
        'subjects', 'targets', 'streams', 'is_active',
    ];

    protected $casts = [
        'subjects'  => 'array',
        'targets'   => 'array',
        'streams'   => 'array',
        'is_active' => 'boolean',
    ];

    public function getLeadTeacherPhotoUrlAttribute(): ?string
    {
        if (! $this->lead_teacher_photo_path) {
            return null;
        }
        return Storage::url($this->lead_teacher_photo_path);
    }

    public function getInitialsAttribute(): string
    {
        $name = preg_replace('/^(Mr|Ms|Mrs|Dr)\.\s+/', '', $this->lead_teacher);
        return collect(explode(' ', $name))
            ->map(fn ($p) => strtoupper(substr($p, 0, 1)))
            ->take(2)
            ->join('');
    }
}
