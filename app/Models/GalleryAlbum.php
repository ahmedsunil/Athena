<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryAlbum extends Model
{
    protected $fillable = [
        'sort_order', 'title', 'category', 'date',
        'photo_count', 'cover_image_path', 'facebook_url', 'is_active',
    ];

    protected $casts = [
        'date'      => 'date',
        'is_active' => 'boolean',
    ];

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image_path ? Storage::url($this->cover_image_path) : null;
    }
}
