<?php

namespace App\Livewire\Cms\HomeSlides;

use App\Models\HomeSlide;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeSlidesIndex extends Component
{
    use WithFileUploads;

    public string $title_en = '';
    public string $title_dv = '';
    public string $description_en = '';
    public string $description_dv = '';
    public string $button_1_label_en = '';
    public string $button_1_label_dv = '';
    public string $button_1_link_key = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $image = null;
    public ?string $existing_image = null;
    public bool $imageRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title_en'          => ['required', 'string', 'max:255'],
            'title_dv'          => ['nullable', 'string', 'max:255'],
            'description_en'    => ['nullable', 'string'],
            'description_dv'    => ['nullable', 'string'],
            'button_1_label_en' => ['nullable', 'string', 'max:100'],
            'button_1_label_dv' => ['nullable', 'string', 'max:100'],
            'button_1_link_key' => ['nullable', 'string'],
            'is_active'         => ['boolean'],
            'sort_order'        => ['integer', 'min:0'],
            'image'             => ['nullable', 'image', 'max:4096'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'          => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'description'    => ['en' => $this->description_en, 'dv' => $this->description_dv],
            'button_1_label' => ['en' => $this->button_1_label_en, 'dv' => $this->button_1_label_dv],
            'button_1_link_key' => $this->button_1_link_key,
            'is_active'      => $this->is_active,
            'sort_order'     => $this->sort_order,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('slides', 'public');
        } elseif ($this->imageRemoved) {
            $data['image_path'] = null;
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
        $this->title_en          = $slide->getTranslation('title', 'en', false) ?? '';
        $this->title_dv          = $slide->getTranslation('title', 'dv', false) ?? '';
        $this->description_en    = $slide->getTranslation('description', 'en', false) ?? '';
        $this->description_dv    = $slide->getTranslation('description', 'dv', false) ?? '';
        $this->button_1_label_en = $slide->getTranslation('button_1_label', 'en', false) ?? '';
        $this->button_1_label_dv = $slide->getTranslation('button_1_label', 'dv', false) ?? '';
        $this->button_1_link_key = $slide->button_1_link_key ?? '';
        $this->is_active         = $slide->is_active;
        $this->sort_order        = $slide->sort_order;
        $this->existing_image    = $slide->image_path;
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->existing_image = null;
        $this->imageRemoved = true;
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
        $this->reset([
            'title_en', 'title_dv', 'description_en', 'description_dv',
            'button_1_label_en', 'button_1_label_dv', 'button_1_link_key',
            'is_active', 'sort_order', 'image', 'existing_image', 'imageRemoved', 'editingId',
        ]);
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
