<?php

namespace App\Http\Resources;

use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonException;

class HomeApiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $payload = $this->decodedPayload();
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
                'href' => '/events/'.($event->slug ?: str($event->title)->slug()->toString()),
            ])
            ->values()
            ->all();
    }

    private function decodedPayload(): array
    {
        foreach ([$this->resource?->payload, $this->defaultPayloadJson(), '{}'] as $payload) {
            if (! is_string($payload) || trim($payload) === '') {
                continue;
            }

            try {
                $decoded = json_decode($payload, true, flags: JSON_THROW_ON_ERROR);

                return is_array($decoded) ? $decoded : [];
            } catch (JsonException) {
                //
            }
        }

        return [];
    }

    private function defaultPayloadJson(): ?string
    {
        $path = base_path('api_jsons/home.json');

        return file_exists($path) ? file_get_contents($path) : null;
    }
}
