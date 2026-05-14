<?php

namespace App\Livewire\Website;

use App\Models\DigitalServiceDocument;
use App\Models\Event;
use App\Models\GalleryAlbum;
use Livewire\Component;

class Search extends Component
{
    public string $query = '';

    public function getResultsProperty(): array
    {
        $q = $this->normalizedQuery();

        if (mb_strlen($q) < 2) {
            return [];
        }

        $like = '%' . $this->escapeLike($q) . '%';
        $results = [];

        Event::where('is_active', true)
            ->where(function ($builder) use ($like) {
                $builder->where('title', 'like', $like)
                    ->orWhere('short_description', 'like', $like);
            })
            ->orderByDesc('date_start')
            ->limit(5)
            ->get(['title', 'slug', 'short_description'])
            ->each(function ($event) use (&$results) {
                $results[] = [
                    'section' => 'Events',
                    'color'   => 'sky',
                    'title'   => $event->title,
                    'snippet' => $event->short_description,
                    'url'     => route('events.show', $event->slug),
                ];
            });

        GalleryAlbum::where('is_active', true)
            ->where('title', 'like', $like)
            ->orderByDesc('date')
            ->limit(4)
            ->get(['title', 'category'])
            ->each(function ($album) use (&$results) {
                $results[] = [
                    'section' => 'Gallery',
                    'color'   => 'violet',
                    'title'   => $album->title,
                    'snippet' => $album->category,
                    'url'     => route('gallery.index'),
                ];
            });

        DigitalServiceDocument::where('is_active', true)
            ->where(function ($builder) use ($like) {
                $builder->where('title', 'like', $like)
                    ->orWhere('category', 'like', $like);
            })
            ->orderByDesc('published_at')
            ->limit(4)
            ->get(['title', 'category'])
            ->each(function ($doc) use (&$results) {
                $results[] = [
                    'section' => 'Downloads',
                    'color'   => 'amber',
                    'title'   => $doc->title,
                    'snippet' => $doc->category,
                    'url'     => route('digital-services.index'),
                ];
            });

        return $results;
    }

    private function normalizedQuery(): string
    {
        return mb_substr(preg_replace('/\s+/', ' ', trim($this->query)) ?? '', 0, 80);
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }

    public function render()
    {
        return view('livewire.website.search', [
            'results' => $this->results,
        ]);
    }
}
