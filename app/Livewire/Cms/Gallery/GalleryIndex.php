<?php

namespace App\Livewire\Cms\Gallery;

use App\Models\GalleryAlbum;
use Livewire\Component;
use Livewire\WithPagination;

class GalleryIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        GalleryAlbum::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Album deleted.');
    }

    public function render()
    {
        return view('livewire.cms.gallery.gallery-index', [
            'albums' => GalleryAlbum::orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Gallery — Albums']);
    }
}
