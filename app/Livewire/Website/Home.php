<?php

namespace App\Livewire\Website;

use App\Models\HomeSlide;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.website.home', [
            'slides' => HomeSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.web');
    }
}
