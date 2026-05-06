<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function home(): Response
    {
        return $this->staticPage('index');
    }

    public function about(): Response
    {
        return $this->staticPage('about');
    }

    public function academics(): Response
    {
        return $this->staticPage('academics');
    }

    public function admissions(): Response
    {
        return $this->staticPage('admissions');
    }

    public function events(): Response
    {
        return $this->staticPage('events');
    }

    public function event(string $slug): Response
    {
        $events = collect($this->json('events')['events'] ?? []);
        $event = $events->first(fn (array $event): bool => $this->slug($event['title']) === $slug || ($event['id'] ?? '') === $slug);

        abort_unless($event, 404);

        return $this->staticEventPage($event['id']);
    }

    public function legacyEvent(): Response
    {
        $id = request('id');

        if (! $id) {
            return redirect()->route('site.events.index');
        }

        return $this->staticEventPage((string) $id);
    }

    public function studentLife(): Response
    {
        return $this->staticPage('student-life');
    }

    public function gallery(): Response
    {
        return $this->staticPage('gallery');
    }

    public function downloads(): Response
    {
        return $this->staticPage('downloads');
    }

    public function digitalServices(): Response
    {
        return $this->staticPage('digital-services');
    }

    public function search(): Response
    {
        return $this->staticPage('search');
    }

    private function json(string $name): array
    {
        $path = base_path("docs/static_site/api/{$name}.json");

        if (! File::exists($path)) {
            return [];
        }

        return json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);
    }

    private function slug(string $value): string
    {
        return Str::slug($value);
    }

    private function staticPage(string $name): Response
    {
        $path = base_path("docs/static_site/{$name}.html");
        abort_unless(File::exists($path), 404);

        return response($this->normalizeStaticHtml(File::get($path)));
    }

    private function staticEventPage(string $id): Response
    {
        $path = base_path('docs/static_site/event.html');
        abort_unless(File::exists($path), 404);

        $html = File::get($path);
        $html = str_replace(
            "const id = new URLSearchParams(window.location.search).get('id');",
            "const id = ".json_encode($id).";",
            $html
        );
        $html = str_replace("if (!id) { window.location.replace('events.html'); return; }\n  ", '', $html);

        return response($this->normalizeStaticHtml($html));
    }

    private function normalizeStaticHtml(string $html): string
    {
        return str_replace(
            [
                'href="index.html"',
                'href="about.html"',
                'href="events.html"',
                'href="academics.html"',
                'href="admissions.html"',
                'href="student-life.html"',
                'href="gallery.html"',
                'href="downloads.html"',
                'href="digital-services.html"',
                'href="search.html"',
            ],
            [
                'href="/"',
                'href="/about"',
                'href="/events"',
                'href="/academics"',
                'href="/admissions"',
                'href="/student-life"',
                'href="/gallery"',
                'href="/downloads"',
                'href="/digital-services"',
                'href="/search"',
            ],
            $html
        );
    }
}
