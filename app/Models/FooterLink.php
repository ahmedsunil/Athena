<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $fillable = ['label', 'label_dv', 'link_key', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
