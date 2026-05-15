<?php

namespace App\Livewire\Cms\Gallery;

use App\Models\GalleryAlbum;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalleryIndex extends Component
{
    use WithFileUploads;

    public string $title_en = '';
    public string $title_dv = '';
    public string $category = 'Events';
    public string $date = '';
    public int $photo_count = 0;
    public string $facebook_url = '';
    public $cover_image = null;
    public ?string $existing_cover = null;
    public bool $coverRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    public array $categories = ['Events', 'Sports', 'Graduation', 'Cultural', 'Academic', 'Trips'];

    protected function rules(): array
    {
        return [
            'title_en'    => ['required', 'string', 'max:255'],
            'title_dv'    => ['nullable', 'string', 'max:255'],
            'category'    => ['required', 'in:Events,Sports,Graduation,Cultural,Academic,Trips'],
            'date'        => ['required', 'date'],
            'photo_count' => ['integer', 'min:0'],
            'facebook_url'=> ['nullable', 'url', 'max:500'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'category'     => $this->category,
            'date'         => $this->date,
            'photo_count'  => $this->photo_count,
            'facebook_url' => $this->facebook_url ?: null,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
        ];

        if ($this->cover_image) {
            $data['cover_image_path'] = $this->cover_image->store('gallery/covers', 'public');
        } elseif ($this->coverRemoved) {
            $data['cover_image_path'] = null;
        }

        if ($this->editingId) {
            GalleryAlbum::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Album updated.');
        } else {
            GalleryAlbum::create($data);
            $this->dispatch('toast', message: 'Album added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $album = GalleryAlbum::findOrFail($id);
        $this->editingId      = $album->id;
        $this->title_en       = $album->getTranslation('title', 'en', false) ?? '';
        $this->title_dv       = $album->getTranslation('title', 'dv', false) ?? '';
        $this->category       = $album->category;
        $this->date           = $album->date->format('Y-m-d');
        $this->photo_count    = $album->photo_count;
        $this->facebook_url   = $album->facebook_url ?? '';
        $this->existing_cover = $album->cover_image_path;
        $this->coverRemoved   = false;
        $this->sort_order     = $album->sort_order;
        $this->is_active      = $album->is_active;
    }

    public function delete(int $id): void
    {
        GalleryAlbum::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Album deleted.');
    }

    public function removeCover(): void
    {
        $this->cover_image    = null;
        $this->existing_cover = null;
        $this->coverRemoved   = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'title_en', 'title_dv', 'date', 'facebook_url',
            'cover_image', 'existing_cover', 'coverRemoved', 'editingId',
        ]);
        $this->category    = 'Events';
        $this->photo_count = 0;
        $this->is_active   = true;
        $this->sort_order  = 0;
    }

    public function render()
    {
        return view('livewire.cms.gallery.gallery-index', [
            'albums' => GalleryAlbum::orderBy('date', 'desc')->orderBy('id', 'desc')->get(),
        ])->layout('layouts.app', ['title' => 'Gallery — Albums']);
    }
}
