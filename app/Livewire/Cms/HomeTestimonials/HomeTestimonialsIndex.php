<?php

namespace App\Livewire\Cms\HomeTestimonials;

use App\Models\HomeTestimonial;
use Livewire\Component;
use Livewire\WithPagination;

class HomeTestimonialsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        HomeTestimonial::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Testimonial deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home-testimonials.home-testimonials-index', [
            'testimonials' => HomeTestimonial::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Testimonials']);
    }
}
