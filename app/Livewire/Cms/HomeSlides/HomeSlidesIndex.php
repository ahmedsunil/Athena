<?php

namespace App\Livewire\Cms\HomeSlides;

use App\Models\HomeSlide;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeSlidesIndex extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public string $button_1_label = '';
    public string $button_1_link_key = '';
    public string $button_2_label = '';
    public string $button_2_link_key = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $image = null;
    public ?string $existing_image = null;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['nullable', 'string'],
            'button_1_label'   => ['nullable', 'string', 'max:100'],
            'button_1_link_key' => ['nullable', 'string'],
            'button_2_label'   => ['nullable', 'string', 'max:100'],
            'button_2_link_key' => ['nullable', 'string'],
            'is_active'        => ['boolean'],
            'sort_order'       => ['integer', 'min:0'],
            'image'            => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'             => $this->title,
            'description'       => $this->description,
            'button_1_label'    => $this->button_1_label,
            'button_1_link_key' => $this->button_1_link_key,
            'button_2_label'    => $this->button_2_label,
            'button_2_link_key' => $this->button_2_link_key,
            'is_active'         => $this->is_active,
            'sort_order'        => $this->sort_order,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('slides', 'public');
        }

        if ($this->editingId) {
            HomeSlide::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Slide updated.');
        } else {
            HomeSlide::create($data);
            $this->dispatch('toast', message: 'Slide created.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $slide = HomeSlide::findOrFail($id);
        $this->editingId         = $slide->id;
        $this->title             = $slide->title;
        $this->description       = $slide->description ?? '';
        $this->button_1_label    = $slide->button_1_label ?? '';
        $this->button_1_link_key = $slide->button_1_link_key ?? '';
        $this->button_2_label    = $slide->button_2_label ?? '';
        $this->button_2_link_key = $slide->button_2_link_key ?? '';
        $this->is_active         = $slide->is_active;
        $this->sort_order        = $slide->sort_order;
        $this->existing_image    = $slide->image_path;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        HomeSlide::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Slide deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'description', 'button_1_label', 'button_1_link_key',
            'button_2_label', 'button_2_link_key', 'is_active', 'sort_order',
            'image', 'existing_image', 'editingId']);
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.cms.home-slides.home-slides-index', [
            'slides'   => HomeSlide::orderBy('sort_order')->orderBy('id')->get(),
            'linkKeys' => config('links'),
        ])->layout('layouts.app', ['title' => 'Home Slides']);
    }
}
