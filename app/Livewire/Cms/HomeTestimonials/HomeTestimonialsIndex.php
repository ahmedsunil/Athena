<?php

namespace App\Livewire\Cms\HomeTestimonials;

use App\Models\HomeTestimonial;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeTestimonialsIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $previous_designation = '';
    public string $current_designation = '';
    public string $message = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:255'],
            'previous_designation'  => ['nullable', 'string', 'max:255'],
            'current_designation'   => ['nullable', 'string', 'max:255'],
            'message'               => ['required', 'string'],
            'is_active'             => ['boolean'],
            'sort_order'            => ['integer', 'min:0'],
            'photo'                 => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'                  => $this->name,
            'previous_designation'  => $this->previous_designation,
            'current_designation'   => $this->current_designation,
            'message'               => $this->message,
            'is_active'             => $this->is_active,
            'sort_order'            => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('testimonials', 'public');
        }

        if ($this->editingId) {
            HomeTestimonial::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Testimonial updated.');
        } else {
            HomeTestimonial::create($data);
            $this->dispatch('toast', message: 'Testimonial created.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = HomeTestimonial::findOrFail($id);
        $this->editingId              = $item->id;
        $this->name                   = $item->name;
        $this->previous_designation   = $item->previous_designation ?? '';
        $this->current_designation    = $item->current_designation ?? '';
        $this->message                = $item->message;
        $this->is_active              = $item->is_active;
        $this->sort_order             = $item->sort_order;
        $this->existing_photo         = $item->photo_path;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        HomeTestimonial::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Testimonial deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'previous_designation', 'current_designation', 'message',
            'is_active', 'sort_order', 'photo', 'existing_photo', 'editingId']);
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.cms.home-testimonials.home-testimonials-index', [
            'testimonials' => HomeTestimonial::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Testimonials']);
    }
}
