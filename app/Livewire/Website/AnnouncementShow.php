<?php

namespace App\Livewire\Website;

use App\Models\Announcement;
use Livewire\Component;

class AnnouncementShow extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.website.announcement-show', [
            'announcement' => Announcement::where('slug', $this->slug)
                ->where('is_active', true)
                ->firstOrFail(),
        ])->layout('layouts.web');
    }
}
