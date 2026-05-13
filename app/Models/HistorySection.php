<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorySection extends Model
{
    protected $fillable = ['title', 'year_label', 'body', 'sort_order'];
}
