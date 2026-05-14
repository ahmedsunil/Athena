<?php

namespace App\Livewire\Website;

use App\Models\AcademicLevel;
use App\Models\AcademicsOverview;
use Livewire\Component;

class Academics extends Component
{
    public function render()
    {
        return view('livewire.website.academics', [
            'overview' => AcademicsOverview::singleton(),
            'levels'   => AcademicLevel::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ])->layout('layouts.web');
    }
}
