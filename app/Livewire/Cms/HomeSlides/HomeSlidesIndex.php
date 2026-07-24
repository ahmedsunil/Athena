<?php

namespace App\Livewire\Cms\HomeSlides;

use App\Models\HomeSlide;
use Livewire\Component;
use Livewire\WithPagination;

class HomeSlidesIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        HomeSlide::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Slide deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home-slides.home-slides-index', [
            'slides' => HomeSlide::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Home Slides']);
    }
}
