<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = ['label', 'link_key', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
