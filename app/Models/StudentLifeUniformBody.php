<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

use Spatie\Translatable\HasTranslations;
class StudentLifeUniformBody extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description', 'meeting_schedule', 'patron_role'];

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
            'rose'    => ['bg' => 'bg-[#002366]',    'text' => 'text-[#002366]',    'bg_light' => 'bg-[#002366]/5'],
            'sky'     => ['bg' => 'bg-sky-500',     'text' => 'text-sky-600',     'bg_light' => 'bg-sky-50'],
            'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'bg_light' => 'bg-emerald-50'],
            'amber'   => ['bg' => 'bg-amber-500',   'text' => 'text-amber-600',   'bg_light' => 'bg-amber-50'],
            'violet'  => ['bg' => 'bg-violet-500',  'text' => 'text-violet-600',  'bg_light' => 'bg-violet-50'],
            'teal'    => ['bg' => 'bg-teal-500',    'text' => 'text-teal-600',    'bg_light' => 'bg-teal-50'],
            default   => ['bg' => 'bg-slate-400',   'text' => 'text-slate-600',   'bg_light' => 'bg-slate-50'],
        };
    }

    public function people(): MorphMany
    {
        return $this->morphMany(StudentLifePerson::class, 'personable');
    }
}
