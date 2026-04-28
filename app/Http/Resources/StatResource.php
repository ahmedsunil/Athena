<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'    => 'stat-' . $this->id,
            'value' => $this->value,
            'label' => $this->label,
        ];
    }
}
