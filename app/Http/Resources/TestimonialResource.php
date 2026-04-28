<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => 'test-' . $this->id,
            'quote'    => $this->quote,
            'author'   => $this->author,
            'role'     => $this->role,
            'photoUrl' => $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null,
        ];
    }
}
