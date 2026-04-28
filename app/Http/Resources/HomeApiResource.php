<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeApiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $payload = json_decode($this->resource?->payload ?? '{}', true, flags: JSON_THROW_ON_ERROR);
        $payload['featuredEvents'] = $this->featuredEvents();

        return $payload;
    }

    public function toResponse($request): JsonResponse
    {
        return response()
            ->json($this->toArray($request), options: JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function featuredEvents(): array
    {
        return Event::where('is_featured', true)
            ->orderBy('featured_sort_order')
            ->orderBy('date_start')
            ->get()
            ->map(fn (Event $event) => [
                'id' => $event->public_id,
                'title' => $event->title,
                'date' => $event->date_start?->format('Y-m-d'),
                'description' => $event->short_description,
                'href' => '/events/' . str($event->title)->slug(),
            ])
            ->values()
            ->all();
    }
}
