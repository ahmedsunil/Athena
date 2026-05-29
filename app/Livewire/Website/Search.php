<?php

namespace App\Livewire\Website;

use App\Models\DigitalServiceDocument;
use App\Models\Event;
use App\Models\Announcement;
use Livewire\Attributes\On;
use Livewire\Component;

class Search extends Component
{
    public string $query = '';
    public bool $open = false;

    #[On('openSearch')]
    public function open(): void
    {
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
    }

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

        Announcement::where('is_active', true)
            ->where(function ($builder) use ($like) {
                $builder->where('title', 'like', $like)
                    ->orWhere('category', 'like', $like)
                    ->orWhere('description', 'like', $like);
            })
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get(['title', 'slug', 'category', 'description'])
            ->each(function ($announcement) use (&$results) {
                $results[] = [
                    'section' => 'Announcements',
                    'color'   => 'rose',
                    'title'   => $announcement->title,
                    'snippet' => $announcement->category ?: $announcement->description,
                    'url'     => route('announcements.show', $announcement->slug),
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
