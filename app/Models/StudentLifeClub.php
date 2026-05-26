<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

use Spatie\Translatable\HasTranslations;
class StudentLifeClub extends Model
{
    use HasTranslations;

    public array $translatable = ['name', 'description', 'meeting_schedule', 'patron_role'];

    protected $table = 'student_life_clubs';

    protected $fillable = [
        'sort_order', 'name', 'description', 'meeting_schedule',
        'patron_name', 'patron_role', 'president_name', 'president_class',
        'logo_path', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }

    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        $initials = array_map(fn($w) => strtoupper($w[0] ?? ''), $words);
        return implode('', array_slice(array_filter($initials), 0, 2));
    }

    public function people(): MorphMany
    {
        return $this->morphMany(StudentLifePerson::class, 'personable');
    }
}
