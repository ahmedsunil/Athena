<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SlideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => 'slide-' . $this->id,
            'imageUrl' => $this->image_path ? Storage::disk('public')->url($this->image_path) : null,
            'title'    => $this->title,
            'subtitle' => $this->subtitle,
            'ctaLabel' => $this->cta_label,
            'ctaHref'  => $this->cta_href,
        ];
    }
}
