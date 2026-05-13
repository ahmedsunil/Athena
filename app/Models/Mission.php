<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = ['mission', 'vision'];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
