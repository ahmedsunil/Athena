<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        'school_name', 'motto', 'short_description', 'logo_path',
        'email', 'phone', 'address', 'island', 'atoll', 'country',
        'principal_name', 'principal_designation', 'principal_message', 'principal_photo_path',
    ];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
