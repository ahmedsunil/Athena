<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomeQuickAccess extends Model
{
    use HasTranslations;

    public array $translatable = ['title'];

    protected $table = 'home_quick_access';

    protected $fillable = ['icon_key', 'title', 'link_key', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];
}
