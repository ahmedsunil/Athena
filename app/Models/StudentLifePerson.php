<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class StudentLifePerson extends Model
{
    protected $fillable = [
        'personable_type',
        'personable_id',
        'year',
        'name',
        'designation',
        'grade',
        'avatar_path',
        'is_teacher_in_charge',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'year' => 'integer',
        'is_teacher_in_charge' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function personable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar_path ? Storage::url($this->avatar_path) : null;
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $initials = array_map(fn ($word) => strtoupper($word[0] ?? ''), $words ?: []);

        return implode('', array_slice(array_filter($initials), 0, 2)) ?: 'HS';
    }
}
