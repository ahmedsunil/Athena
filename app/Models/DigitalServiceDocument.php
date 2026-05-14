<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DigitalServiceDocument extends Model
{
    protected $fillable = [
        'sort_order', 'title', 'category', 'file_type', 'file_size',
        'audience', 'published_at', 'file_path', 'is_active',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_active'    => 'boolean',
    ];

    public function getDownloadUrlAttribute(): ?string
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }
}
