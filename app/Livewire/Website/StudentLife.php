<?php

namespace App\Livewire\Website;

use Livewire\Component;

class StudentLife extends Component
{
    public function render()
    {
        return view('livewire.website.student-life')->layout('layouts.web');
    }
}
