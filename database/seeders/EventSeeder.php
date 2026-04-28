<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $eventsPath = base_path('api_jsons/events.json');
        $homePath = base_path('api_jsons/home.json');

        if (! File::exists($eventsPath)) {
            return;
        }

        $eventsJson = json_decode(File::get($eventsPath), true, flags: JSON_THROW_ON_ERROR);
        $homeJson = File::exists($homePath)
            ? json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR)
            : [];

        $featuredHrefs = collect($homeJson['featuredEvents'] ?? [])->pluck('href')->values();
        $defaultFeaturedOrder = [
            'evt-002' => 0,
            'evt-003' => 1,
            'evt-005' => 2,
        ];

        foreach ($eventsJson['events'] ?? [] as $event) {
            $href = '/events/' . str($event['title'])->slug();
            $featuredIndex = $featuredHrefs->search($href);
            $featuredOrder = $featuredIndex === false
                ? ($defaultFeaturedOrder[$event['id']] ?? false)
                : $featuredIndex;

            Event::updateOrCreate(
                ['public_id' => $event['id']],
                [
                    'status' => $event['status'],
                    'title' => $event['title'],
                    'date_start' => $event['dateStart'],
                    'date_end' => $event['dateEnd'],
                    'location' => $event['location'],
                    'cover_image_url' => $event['coverImageUrl'] ?? null,
                    'short_description' => $event['shortDescription'],
                    'full_description' => $event['fullDescription'],
                    'attachments' => $event['attachments'] ?? [],
                    'contact' => $event['contact'] ?? null,
                    'is_featured' => $featuredOrder !== false,
                    'featured_sort_order' => $featuredOrder === false ? 0 : $featuredOrder,
                ]
            );
        }
    }
}
