<?php

namespace App\Livewire\Cms\HomeTestimonials;

use App\Models\HomeTestimonial;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeTestimonialForm extends Component
{
    use WithFileUploads;

    public ?int $itemId = null;

    public string $name = '';
    public string $previous_designation_en = '';
    public string $current_designation_en = '';
    public string $message_en = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;

    public function mount(?int $itemId = null): void
    {
        $this->itemId = $itemId;

        if ($itemId) {
            $item = HomeTestimonial::findOrFail($itemId);
            $this->name                       = $item->name;
            $this->previous_designation_en    = $item->getTranslation('previous_designation', 'en', false) ?? '';
            $this->current_designation_en     = $item->getTranslation('current_designation', 'en', false) ?? '';
            $this->message_en                 = $item->getTranslation('message', 'en', false) ?? '';
            $this->is_active                  = $item->is_active;
            $this->sort_order                 = $item->sort_order;
            $this->existing_photo             = $item->photo_path;
        }
    }

    protected function rules(): array
    {
        return [
            'name'                       => ['required', 'string', 'max:255'],
            'previous_designation_en'    => ['nullable', 'string', 'max:255'],
            'current_designation_en'     => ['nullable', 'string', 'max:255'],
            'message_en'                 => ['required', 'string'],
            'is_active'                  => ['boolean'],
            'sort_order'                 => ['integer', 'min:0'],
            'photo'                      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'                 => $this->name,
            'previous_designation' => ['en' => $this->previous_designation_en],
            'current_designation'  => ['en' => $this->current_designation_en],
            'message'              => ['en' => $this->message_en],
            'is_active'            => $this->is_active,
            'sort_order'           => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('testimonials', 'public');
        }

        if ($this->itemId) {
            HomeTestimonial::findOrFail($this->itemId)->update($data);
            $this->dispatch('toast', message: 'Testimonial updated.');
        } else {
            HomeTestimonial::create($data);
            $this->dispatch('toast', message: 'Testimonial created.');
        }

        $this->redirect(route('cms.home.testimonials'), navigate: true);
    }

    public function render()
    {
        $title = $this->itemId ? 'Edit Testimonial' : 'New Testimonial';
        return view('livewire.cms.home-testimonials.home-testimonials-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
