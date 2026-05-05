<?php

namespace App\Livewire\Cms\Links;

use App\Models\Link;
use Livewire\Attributes\Locked;
use Livewire\Component;

class LinksList extends Component
{
    #[Locked]
    public ?int $deletingId = null;

    public bool $confirmingDelete = false;

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDelete = true;
    }

    public function delete(): void
    {
        Link::findOrFail($this->deletingId)->delete();
        $this->confirmingDelete = false;
        $this->deletingId = null;
        $this->dispatch('toast', message: 'Link deleted.');
    }

    public function render()
    {
        return view('livewire.cms.links.links-list', [
            'links' => Link::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Links']);
    }
}
