<?php

namespace App\Livewire\Website;

use App\Models\Event;
use App\Models\HomeQuickAccess;
use App\Models\HomeSlide;
use App\Models\HomeStat;
use App\Models\HomeTestimonial;
use App\Models\SchoolProfile;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $profile = SchoolProfile::singleton();

        return view('livewire.website.home', [
            'slides' => HomeSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'stats' => HomeStat::where('is_active', true)->orderBy('sort_order')->get(),
            'quickAccess' => HomeQuickAccess::where('is_active', true)->orderBy('sort_order')->get(),
            'testimonials' => HomeTestimonial::where('is_active', true)->orderBy('sort_order')->get(),
            'profile' => $profile,
            'featuredEvents' => Event::where('is_featured', true)
                ->where('is_active', true)
                ->orderBy('featured_sort_order')
                ->take(3)
                ->get(),
        ])->layout('layouts.web');
    }
}
