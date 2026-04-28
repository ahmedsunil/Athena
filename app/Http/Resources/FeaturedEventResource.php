<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => 'evt-' . $this->id,
            'title'       => $this->title,
            'date'        => $this->date->format('Y-m-d'),
            'description' => $this->description,
            'href'        => $this->href,
        ];
    }
}
