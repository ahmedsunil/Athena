<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    public $guarded = [];

    protected $casts = ['founded_year' => 'integer'];
}
