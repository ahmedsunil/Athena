<?php

namespace App\Livewire\Cms\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EventForm extends Component
{
    use WithFileUploads;

    public ?int $eventId = null;

    public string $title_en = '';
    public string $slug = '';
    public string $status = 'upcoming';
    public string $date_start = '';
    public string $time_start = '';
    public string $date_end = '';
    public string $time_end = '';
    public string $location_en = '';
    public $cover_image = null;
    public ?string $existing_cover_image = null;
    public bool $coverImageRemoved = false;
    public string $short_description_en = '';
    public string $full_description_en = '';
    public string $contact = '';
    public array $attachments = [];
    public bool $is_featured = false;
    public int $featured_sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $eventId = null): void
    {
        if ($eventId) {
            $event = Event::findOrFail($eventId);
            $this->eventId                = $event->id;
            $this->title_en               = $event->getTranslation('title', 'en', false) ?? '';
            $this->slug                   = $event->slug;
            $this->status                 = $event->status;
            $this->date_start             = $event->date_start->format('Y-m-d');
            $this->time_start             = $event->time_start ? substr($event->time_start, 0, 5) : '';
            $this->date_end               = $event->date_end?->format('Y-m-d') ?? '';
            $this->time_end               = $event->time_end ? substr($event->time_end, 0, 5) : '';
            $this->location_en            = $event->getTranslation('location', 'en', false) ?? '';
            $this->existing_cover_image   = $event->cover_image_path;
            $this->coverImageRemoved      = false;
            $this->short_description_en   = $event->getTranslation('short_description', 'en', false) ?? '';
            $this->full_description_en    = $event->getTranslation('full_description', 'en', false) ?? '';
            $this->contact                = $event->contact ?? '';
            $this->attachments            = $event->attachments ?? [];
            $this->is_featured            = $event->is_featured;
            $this->featured_sort_order    = $event->featured_sort_order;
            $this->is_active              = $event->is_active;
        }
    }

    public function updatedTitleEn(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'             => ['required', 'string', 'max:255'],
            'slug'                 => ['required', 'string', 'max:255'],
            'status'               => ['required', 'in:ongoing,upcoming,completed'],
            'date_start'           => ['required', 'date'],
            'time_start'           => ['nullable', 'date_format:H:i'],
            'date_end'             => ['nullable', 'date', 'after_or_equal:date_start'],
            'time_end'             => ['nullable', 'date_format:H:i'],
            'location_en'          => ['required', 'string', 'max:255'],
            'cover_image'          => ['nullable', 'image', 'max:4096'],
            'short_description_en' => ['required', 'string'],
            'full_description_en'  => ['nullable', 'string'],
            'contact'              => ['nullable', 'string', 'max:255'],
            'attachments'          => ['nullable', 'array'],
            'attachments.*.label'  => ['nullable', 'string', 'max:255'],
            'attachments.*.url'    => ['nullable', 'url'],
            'is_featured'          => ['boolean'],
            'featured_sort_order'  => ['integer', 'min:0'],
            'is_active'            => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'             => ['en' => $this->title_en],
            'slug'              => Str::slug($this->slug),
            'status'            => $this->status,
            'date_start'        => $this->date_start,
            'time_start'        => $this->time_start ?: null,
            'date_end'          => $this->date_end ?: null,
            'time_end'          => $this->time_end ?: null,
            'location'          => ['en' => $this->location_en],
            'short_description' => ['en' => $this->short_description_en],
            'full_description'  => $this->full_description_en ? ['en' => $this->full_description_en] : null,
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

        if ($this->eventId) {
            Event::findOrFail($this->eventId)->update($data);
            $this->dispatch('toast', message: 'Event updated.');
        } else {
            Event::create($data);
            $this->dispatch('toast', message: 'Event created.');
        }

        $this->redirect(route('cms.events.index'), navigate: true);
    }

    public function removeCoverImage(): void
    {
        $this->cover_image          = null;
        $this->existing_cover_image = null;
        $this->coverImageRemoved    = true;
    }

    public function addAttachment(): void
    {
        $this->attachments[] = ['label' => '', 'url' => ''];
    }

    public function removeAttachment(int $i): void
    {
        array_splice($this->attachments, $i, 1);
    }

    public function render()
    {
        $title = $this->eventId ? 'Edit event' : 'Add event';
        return view('livewire.cms.events.event-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
