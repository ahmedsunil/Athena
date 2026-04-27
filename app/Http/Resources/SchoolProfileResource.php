<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'founded_year' => $this->founded_year,
            'motto' => $this->motto,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'logo_path' => $this->logo_path,
            'hero_image_path' => $this->hero_image_path,
            'mission_statement' => $this->mission_statement,
            'vision_statement' => $this->vision_statement,
            'contact' => [
                'address' => $this->contact_address,
                'phone' => $this->contact_phone,
                'email' => $this->contact_email,
            ],
        ];
    }
}
