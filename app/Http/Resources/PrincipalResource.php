<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PrincipalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name'     => $this->name,
            'title'    => $this->title,
            'photoUrl' => $this->photo_path ? Storage::disk('public')->url($this->photo_path) : null,
            'message'  => $this->message,
        ];
    }
}
