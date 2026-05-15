<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
class StudentLifeHouse extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'motto', 'description', 'house_master_role'];

    protected $table = 'student_life_houses';

    protected $fillable = [
        'sort_order', 'name', 'colour', 'motto', 'description',
        'house_master_name', 'house_master_role', 'captain_name', 'captain_class', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getColourClassesAttribute(): array
    {
        return match($this->colour) {
            'rose'    => ['bg' => 'bg-rose-500',    'text' => 'text-rose-600',    'bg_light' => 'bg-rose-50'],
            'sky'     => ['bg' => 'bg-sky-500',     'text' => 'text-sky-600',     'bg_light' => 'bg-sky-50'],
            'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'bg_light' => 'bg-emerald-50'],
            'amber'   => ['bg' => 'bg-amber-500',   'text' => 'text-amber-600',   'bg_light' => 'bg-amber-50'],
            default   => ['bg' => 'bg-slate-400',   'text' => 'text-slate-600',   'bg_light' => 'bg-slate-50'],
        };
    }
}
