<?php
namespace App\Livewire\Cms\Home;

use App\Models\FeaturedEvent;
use App\Models\Principal;
use App\Models\QuickLink;
use App\Models\Slide;
use App\Models\Stat;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class HomeIndex extends Component
{
    public function deleteSlide(int $id): void
    {
        $slide = Slide::findOrFail($id);
        if ($slide->image_path) {
            Storage::disk('public')->delete($slide->image_path);
        }
        $slide->delete();
        $this->dispatch('toast', message: 'Slide deleted.');
    }

    public function deleteStat(int $id): void
    {
        Stat::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Stat deleted.');
    }

    public function deleteFeaturedEvent(int $id): void
    {
        FeaturedEvent::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Event deleted.');
    }

    public function deleteQuickLink(int $id): void
    {
        QuickLink::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Quick link deleted.');
    }

    public function deleteTestimonial(int $id): void
    {
        $t = Testimonial::findOrFail($id);
        if ($t->photo_path) {
            Storage::disk('public')->delete($t->photo_path);
        }
        $t->delete();
        $this->dispatch('toast', message: 'Testimonial deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home.home-index', [
            'slides'       => Slide::orderBy('sort_order')->get(),
            'stats'        => Stat::orderBy('sort_order')->get(),
            'principal'    => Principal::first(),
            'events'       => FeaturedEvent::orderBy('sort_order')->get(),
            'quickLinks'   => QuickLink::orderBy('sort_order')->get(),
            'testimonials' => Testimonial::orderBy('sort_order')->get(),
        ])->layout('layouts.app', ['title' => 'Home']);
    }
}
