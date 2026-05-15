<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
class DigitalServiceResource extends Model
{
    use HasTranslations;

    public array $translatable = ['title', 'description'];

    use HasFactory;
    protected $fillable = [
        'sort_order', 'title', 'description', 'audience',
        'icon', 'icon_color', 'url', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getIconColorClassesAttribute(): string
    {
        return match($this->icon_color) {
            'sky'     => 'bg-sky-100 text-sky-600',
            'rose'    => 'bg-rose-100 text-rose-600',
            'emerald' => 'bg-emerald-100 text-emerald-600',
            'amber'   => 'bg-amber-100 text-amber-600',
            'violet'  => 'bg-violet-100 text-violet-600',
            default   => 'bg-slate-100 text-slate-600',
        };
    }
}
