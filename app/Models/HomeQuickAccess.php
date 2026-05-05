<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeQuickAccess extends Model
{
    protected $fillable = ['icon_key', 'title', 'link_key', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
