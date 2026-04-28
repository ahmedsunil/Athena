<?php

namespace App\Livewire\Cms\Home;

use App\Models\Event;
use App\Models\HomePage;
use JsonException;
use Livewire\Component;
use Livewire\WithPagination;

class HomeIndex extends Component
{
    use WithPagination;

    public array $meta = [];
    public array $slides = [];
    public array $stats = [];
    public array $principal = [];
    public array $featuredEventIds = [];
    public array $quickLinks = [];
    public array $testimonials = [];
    public array $contact = [];
    public bool $isEditing = false;
    public int $eventsPerPage = 5;

    public function mount(): void
    {
        $homePage = HomePage::firstOrCreate(['id' => 1], [
            'payload' => $this->defaultPayloadJson(),
        ]);

        $this->fillFromPayload($this->decodePayload($homePage->payload));
    }

    public function enableEdit(): void
    {
        $this->isEditing = true;
    }

    public function cancelEdit(): void
    {
        $homePage = HomePage::first();

        $this->fillFromPayload($this->decodePayload($homePage?->payload));
        $this->resetErrorBag();
        $this->isEditing = false;
    }

    public function addSlide(): void
    {
        $this->slides[] = [
            'id' => 'slide-' . (count($this->slides) + 1),
            'imageUrl' => '',
            'title' => '',
            'subtitle' => '',
            'ctaLabel' => '',
            'ctaHref' => '',
        ];
    }

    public function removeSlide(int $index): void
    {
        unset($this->slides[$index]);
        $this->slides = array_values($this->slides);
    }

    public function addStat(): void
    {
        $this->stats[] = [
            'id' => 'stat-' . (count($this->stats) + 1),
            'value' => '',
            'label' => '',
        ];
    }

    public function removeStat(int $index): void
    {
        unset($this->stats[$index]);
        $this->stats = array_values($this->stats);
    }

    public function addQuickLink(): void
    {
        $this->quickLinks[] = [
            'id' => 'ql-' . (count($this->quickLinks) + 1),
            'label' => '',
            'href' => '',
            'icon' => '',
        ];
    }

    public function removeQuickLink(int $index): void
    {
        unset($this->quickLinks[$index]);
        $this->quickLinks = array_values($this->quickLinks);
    }

    public function addTestimonial(): void
    {
        $this->testimonials[] = [
            'id' => 'test-' . (count($this->testimonials) + 1),
            'quote' => '',
            'author' => '',
            'role' => '',
            'photoUrl' => '',
        ];
    }

    public function removeTestimonial(int $index): void
    {
        unset($this->testimonials[$index]);
        $this->testimonials = array_values($this->testimonials);
    }

    public function addContactField(): void
    {
        $this->contact['formFields'][] = '';
    }

    public function removeContactField(int $index): void
    {
        unset($this->contact['formFields'][$index]);
        $this->contact['formFields'] = array_values($this->contact['formFields'] ?? []);
    }

    protected function rules(): array
    {
        return [
            'slides' => ['array'],
            'slides.*.imageUrl' => ['nullable', 'string', 'max:2048'],
            'slides.*.title' => ['required', 'string', 'max:255'],
            'slides.*.subtitle' => ['required', 'string'],
            'slides.*.ctaLabel' => ['required', 'string', 'max:255'],
            'slides.*.ctaHref' => ['required', 'string', 'max:255'],

            'stats' => ['array'],
            'stats.*.value' => ['required', 'string', 'max:64'],
            'stats.*.label' => ['required', 'string', 'max:255'],

            'principal.name' => ['required', 'string', 'max:255'],
            'principal.title' => ['required', 'string', 'max:255'],
            'principal.photoUrl' => ['nullable', 'string', 'max:2048'],
            'principal.message' => ['required', 'string'],

            'featuredEventIds' => ['array'],
            'featuredEventIds.*' => ['string', 'exists:events,public_id'],

            'quickLinks' => ['array'],
            'quickLinks.*.label' => ['required', 'string', 'max:255'],
            'quickLinks.*.href' => ['required', 'string', 'max:255'],
            'quickLinks.*.icon' => ['required', 'string', 'max:255'],

            'testimonials' => ['array'],
            'testimonials.*.quote' => ['required', 'string'],
            'testimonials.*.author' => ['required', 'string', 'max:255'],
            'testimonials.*.role' => ['required', 'string', 'max:255'],
            'testimonials.*.photoUrl' => ['nullable', 'string', 'max:2048'],

            'contact.address' => ['required', 'string', 'max:500'],
            'contact.phone' => ['required', 'string', 'max:255'],
            'contact.email' => ['required', 'email', 'max:255'],
            'contact.formFields' => ['array'],
            'contact.formFields.*' => ['required', 'string', 'max:64'],
        ];
    }

    public function save(): void
    {
        $this->ensureGeneratedIds();
        $this->validate();

        Event::query()->update([
            'is_featured' => false,
            'featured_sort_order' => 0,
        ]);

        foreach (array_values($this->featuredEventIds) as $index => $publicId) {
            Event::where('public_id', $publicId)->update([
                'is_featured' => true,
                'featured_sort_order' => $index,
            ]);
        }

        HomePage::updateOrCreate(
            ['id' => 1],
            ['payload' => $this->encodePayload()]
        );

        $this->isEditing = false;
        $this->dispatch('toast', message: 'Home page saved.');
    }

    public function render()
    {
        return view('livewire.cms.home.home-index')
            ->with([
                'events' => Event::orderByDesc('date_start')
                    ->orderByDesc('id')
                    ->paginate($this->eventsPerPage, pageName: 'eventsPage'),
                'selectedEvents' => Event::whereIn('public_id', $this->featuredEventIds)
                    ->get()
                    ->keyBy('public_id'),
            ])
            ->layout('layouts.app', ['title' => 'Home']);
    }

    private function encodePayload(): string
    {
        $this->ensureGeneratedIds();

        return json_encode([
            '_meta' => $this->meta,
            'slides' => array_values($this->slides),
            'stats' => array_values($this->stats),
            'principal' => $this->principal,
            'quickLinks' => array_values($this->quickLinks),
            'testimonials' => array_values($this->testimonials),
            'contact' => $this->contact,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function decodePayload(?string $payload): array
    {
        try {
            return json_decode($payload ?: $this->defaultPayloadJson(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return json_decode($this->defaultPayloadJson(), true, flags: JSON_THROW_ON_ERROR);
        }
    }

    private function fillFromPayload(array $payload): void
    {
        $this->meta = $payload['_meta'] ?? [];
        $this->slides = $payload['slides'] ?? [];
        $this->stats = $payload['stats'] ?? [];
        $this->principal = array_merge($this->blankPrincipal(), $payload['principal'] ?? []);
        $this->featuredEventIds = Event::where('is_featured', true)
            ->orderBy('featured_sort_order')
            ->orderBy('date_start')
            ->pluck('public_id')
            ->all();
        $this->quickLinks = $payload['quickLinks'] ?? [];
        $this->testimonials = $payload['testimonials'] ?? [];
        $this->contact = array_merge($this->blankContact(), $payload['contact'] ?? []);
        $this->contact['formFields'] = array_values($this->contact['formFields'] ?? ['name', 'email', 'message']);
        $this->ensureGeneratedIds();
    }

    private function ensureGeneratedIds(): void
    {
        $this->slides = $this->withGeneratedIds($this->slides, 'slide');
        $this->stats = $this->withGeneratedIds($this->stats, 'stat');
        $this->quickLinks = $this->withGeneratedIds($this->quickLinks, 'ql');
        $this->testimonials = $this->withGeneratedIds($this->testimonials, 'test');
    }

    private function withGeneratedIds(array $items, string $prefix): array
    {
        return array_map(function (array $item, int $index) use ($prefix) {
            $item['id'] = $item['id'] ?? "{$prefix}-" . ($index + 1);

            return $item;
        }, array_values($items), array_keys(array_values($items)));
    }

    private function defaultPayloadJson(): string
    {
        $path = base_path('api_jsons/home.json');

        if (! file_exists($path)) {
            return '{}';
        }

        return file_get_contents($path);
    }

    private function blankPrincipal(): array
    {
        return [
            'name' => '',
            'title' => '',
            'photoUrl' => '',
            'message' => '',
        ];
    }

    private function blankContact(): array
    {
        return [
            'address' => '',
            'phone' => '',
            'email' => '',
            'formFields' => ['name', 'email', 'message'],
        ];
    }
}
