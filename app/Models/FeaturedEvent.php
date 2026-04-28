<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedEvent extends Model
{
    public $guarded = [];

    protected $casts = ['date' => 'date'];
}
