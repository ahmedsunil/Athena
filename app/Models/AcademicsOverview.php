<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicsOverview extends Model
{
    protected $table = 'academics_overview';

    protected $fillable = ['text', 'curriculum'];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
