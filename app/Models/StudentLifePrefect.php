<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class StudentLifePrefect extends Model
{
    protected $table = 'student_life_prefects';

    protected $fillable = [
        'sort_order', 'name', 'photo_path', 'role', 'class_name', 'quote', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? Storage::url($this->photo_path) : null;
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $initials = array_map(fn($w) => strtoupper($w[0] ?? ''), $words);
        return implode('', array_slice(array_filter($initials), 0, 2));
    }

    public function getRoleColourAttribute(): string
    {
        return match($this->role) {
            'Head Boy', 'Head Girl'   => 'bg-rose-100 text-rose-700 ring-rose-200',
            'Senior Prefect'          => 'bg-violet-100 text-violet-700 ring-violet-200',
            'Sports Prefect'          => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
            'Library Prefect'         => 'bg-sky-100 text-sky-700 ring-sky-200',
            'Social Prefect'          => 'bg-amber-100 text-amber-700 ring-amber-200',
            'Sanitation Prefect'      => 'bg-teal-100 text-teal-700 ring-teal-200',
            'Cultural Prefect'        => 'bg-orange-100 text-orange-700 ring-orange-200',
            default                   => 'bg-slate-100 text-slate-700 ring-slate-200',
        };
    }
}
