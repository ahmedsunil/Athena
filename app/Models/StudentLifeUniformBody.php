<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentLifeUniformBody extends Model
{
    protected $table = 'student_life_uniform_bodies';

    protected $fillable = [
        'sort_order', 'name', 'group_type', 'colour', 'description',
        'meeting_schedule', 'patron_name', 'patron_role',
        'leader_name', 'leader_class', 'logo_path', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    public function getColourClassesAttribute(): array
    {
        return match($this->colour) {
            'rose'    => ['bg' => 'bg-rose-500',    'text' => 'text-rose-600',    'bg_light' => 'bg-rose-50'],
            'sky'     => ['bg' => 'bg-sky-500',     'text' => 'text-sky-600',     'bg_light' => 'bg-sky-50'],
            'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'bg_light' => 'bg-emerald-50'],
            'amber'   => ['bg' => 'bg-amber-500',   'text' => 'text-amber-600',   'bg_light' => 'bg-amber-50'],
            'violet'  => ['bg' => 'bg-violet-500',  'text' => 'text-violet-600',  'bg_light' => 'bg-violet-50'],
            'teal'    => ['bg' => 'bg-teal-500',    'text' => 'text-teal-600',    'bg_light' => 'bg-teal-50'],
            default   => ['bg' => 'bg-slate-400',   'text' => 'text-slate-600',   'bg_light' => 'bg-slate-50'],
        };
    }
}
