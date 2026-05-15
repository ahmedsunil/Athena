<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
class AcademicsOverview extends Model
{
    use HasTranslations;

    public array $translatable = ['text', 'curriculum'];

    protected $table = 'academics_overview';

    protected $fillable = ['text', 'curriculum'];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
