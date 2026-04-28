<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuickLinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => 'ql-' . $this->id,
            'label' => $this->label,
            'href'  => $this->href,
            'icon'  => $this->icon,
        ];
    }
}
