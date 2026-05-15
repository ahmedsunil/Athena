<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Translatable\HasTranslations;
class Mission extends Model
{
    use HasTranslations;

    public array $translatable = ['mission', 'vision'];

    protected $fillable = ['mission', 'vision'];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
