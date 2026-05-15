<?php

namespace App\Livewire\Cms\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EventsIndex extends Component
{
    use WithFileUploads;

    public string $title_en = '';
    public string $title_dv = '';
    public string $slug = '';
    public string $status = 'upcoming';
    public string $date_start = '';
    public string $date_end = '';
    public string $location_en = '';
    public string $location_dv = '';
    public $cover_image = null;
    public ?string $existing_cover_image = null;
    public bool $coverImageRemoved = false;
    public string $short_description_en = '';
    public string $short_description_dv = '';
    public string $full_description_en = '';
    public string $full_description_dv = '';
    public string $contact = '';
    public array $attachments = [];
    public bool $is_featured = false;
    public int $featured_sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title_en'             => ['required', 'string', 'max:255'],
            'title_dv'             => ['nullable', 'string', 'max:255'],
            'slug'                 => ['required', 'string', 'max:255'],
            'status'               => ['required', 'in:ongoing,upcoming,completed'],
            'date_start'           => ['required', 'date'],
            'date_end'             => ['nullable', 'date', 'after_or_equal:date_start'],
            'location_en'          => ['required', 'string', 'max:255'],
            'location_dv'          => ['nullable', 'string', 'max:255'],
            'cover_image'          => ['nullable', 'image', 'max:4096'],
            'short_description_en' => ['required', 'string'],
            'short_description_dv' => ['nullable', 'string'],
            'full_description_en'  => ['nullable', 'string'],
            'full_description_dv'  => ['nullable', 'string'],
            'contact'              => ['nullable', 'string', 'max:255'],
            'attachments'          => ['nullable', 'array'],
            'attachments.*.label'  => ['nullable', 'string', 'max:255'],
            'attachments.*.url'    => ['nullable', 'url'],
            'is_featured'          => ['boolean'],
            'featured_sort_order'  => ['integer', 'min:0'],
            'is_active'            => ['boolean'],
        ];
    }

    public function updatedTitleEn(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'             => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'slug'              => Str::slug($this->slug),
            'status'            => $this->status,
            'date_start'        => $this->date_start,
            'date_end'          => $this->date_end ?: null,
            'location'          => ['en' => $this->location_en, 'dv' => $this->location_dv],
            'short_description' => ['en' => $this->short_description_en, 'dv' => $this->short_description_dv],
            'full_description'  => ($this->full_description_en || $this->full_description_dv)
                                    ? ['en' => $this->full_description_en, 'dv' => $this->full_description_dv]
                                    : null,
            'contact'           => $this->contact ?: null,
            'attachments'       => array_values(array_filter(
                $this->attachments,
                fn($a) => ! empty($a['label']) || ! empty($a['url'])
            )) ?: null,
            'is_featured'         => $this->is_featured,
            'featured_sort_order' => $this->featured_sort_order,
            'is_active'           => $this->is_active,
        ];

        if ($this->cover_image) {
            $data['cover_image_path'] = $this->cover_image->store('events', 'public');
        } elseif ($this->coverImageRemoved) {
            $data['cover_image_path'] = null;
        }

        if ($this->editingId) {
            Event::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Event updated.');
        } else {
            Event::create($data);
            $this->dispatch('toast', message: 'Event created.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $event = Event::findOrFail($id);
        $this->editingId              = $event->id;
        $this->title_en               = $event->getTranslation('title', 'en', false) ?? '';
        $this->title_dv               = $event->getTranslation('title', 'dv', false) ?? '';
        $this->slug                   = $event->slug;
        $this->status                 = $event->status;
        $this->date_start             = $event->date_start->format('Y-m-d');
        $this->date_end               = $event->date_end?->format('Y-m-d') ?? '';
        $this->location_en            = $event->getTranslation('location', 'en', false) ?? '';
        $this->location_dv            = $event->getTranslation('location', 'dv', false) ?? '';
        $this->existing_cover_image   = $event->cover_image_path;
        $this->coverImageRemoved      = false;
        $this->short_description_en   = $event->getTranslation('short_description', 'en', false) ?? '';
        $this->short_description_dv   = $event->getTranslation('short_description', 'dv', false) ?? '';
        $this->full_description_en    = $event->getTranslation('full_description', 'en', false) ?? '';
        $this->full_description_dv    = $event->getTranslation('full_description', 'dv', false) ?? '';
        $this->contact                = $event->contact ?? '';
        $this->attachments            = $event->attachments ?? [];
        $this->is_featured            = $event->is_featured;
        $this->featured_sort_order    = $event->featured_sort_order;
        $this->is_active              = $event->is_active;
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Event deleted.');
    }

    public function removeCoverImage(): void
    {
        $this->cover_image          = null;
        $this->existing_cover_image = null;
        $this->coverImageRemoved    = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function addAttachment(): void
    {
        $this->attachments[] = ['label' => '', 'url' => ''];
    }

    public function removeAttachment(int $i): void
    {
        array_splice($this->attachments, $i, 1);
    }

    private function resetForm(): void
    {
        $this->reset([
            'title_en', 'title_dv', 'slug', 'date_start', 'date_end',
            'location_en', 'location_dv',
            'cover_image', 'existing_cover_image', 'coverImageRemoved',
            'short_description_en', 'short_description_dv',
            'full_description_en', 'full_description_dv',
            'contact', 'attachments', 'is_featured', 'featured_sort_order', 'editingId',
        ]);
        $this->status    = 'upcoming';
        $this->is_active = true;
        $this->featured_sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.events.events-index', [
            'events' => Event::orderByDesc('date_start')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Events']);
    }
}
