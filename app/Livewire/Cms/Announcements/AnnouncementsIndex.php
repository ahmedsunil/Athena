<?php

namespace App\Livewire\Cms\Announcements;

use App\Models\Announcement;
use Livewire\Component;
use Livewire\WithPagination;

class AnnouncementsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        Announcement::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Announcement deleted.');
    }

    public function render()
    {
        return view('livewire.cms.announcements.announcements-index', [
            'announcements' => Announcement::orderBy('sort_order')->orderByDesc('created_at')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Announcements']);
    }
}
