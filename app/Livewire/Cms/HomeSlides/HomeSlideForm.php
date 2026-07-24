<?php

namespace App\Livewire\Cms\HomeSlides;

use App\Models\HomeSlide;
use Livewire\Component;
use Livewire\WithFileUploads;

class HomeSlideForm extends Component
{
    use WithFileUploads;

    public ?int $itemId = null;

    public string $title_en = '';
    public string $description_en = '';
    public string $button_1_label_en = '';
    public string $button_1_link_key = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $image = null;
    public ?string $existing_image = null;
    public bool $imageRemoved = false;

    public function mount(?int $itemId = null): void
    {
        $this->itemId = $itemId;

        if ($itemId) {
            $slide = HomeSlide::findOrFail($itemId);
            $this->title_en          = $slide->getTranslation('title', 'en', false) ?? '';
            $this->description_en    = $slide->getTranslation('description', 'en', false) ?? '';
            $this->button_1_label_en = $slide->getTranslation('button_1_label', 'en', false) ?? '';
            $this->button_1_link_key = $slide->button_1_link_key ?? '';
            $this->is_active         = $slide->is_active;
            $this->sort_order        = $slide->sort_order;
            $this->existing_image    = $slide->image_path;
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'          => ['required', 'string', 'max:255'],
            'description_en'    => ['nullable', 'string'],
            'button_1_label_en' => ['nullable', 'string', 'max:100'],
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
            'title'            => ['en' => $this->title_en],
            'description'      => ['en' => $this->description_en],
            'button_1_label'   => ['en' => $this->button_1_label_en],
            'button_1_link_key' => $this->button_1_link_key,
            'is_active'        => $this->is_active,
            'sort_order'       => $this->sort_order,
        ];

        if ($this->image) {
            $data['image_path'] = $this->image->store('slides', 'public');
        } elseif ($this->imageRemoved) {
            $data['image_path'] = null;
        }

        if ($this->itemId) {
            HomeSlide::findOrFail($this->itemId)->update($data);
            $this->dispatch('toast', message: 'Slide updated.');
        } else {
            HomeSlide::create($data);
            $this->dispatch('toast', message: 'Slide created.');
        }

        $this->redirect(route('cms.home.slides'), navigate: true);
    }

    public function removeImage(): void
    {
        $this->image = null;
        $this->existing_image = null;
        $this->imageRemoved = true;
    }

    public function render()
    {
        $title = $this->itemId ? 'Edit Slide' : 'New Slide';
        return view('livewire.cms.home-slides.home-slides-form', [
            'linkKeys' => config('links'),
        ])->layout('layouts.app', ['title' => $title]);
    }
}
