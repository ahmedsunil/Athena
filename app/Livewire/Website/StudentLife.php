<?php

namespace App\Livewire\Website;

use App\Models\StudentLifeClub;
use App\Models\StudentLifeHouse;
use App\Models\StudentLifePrefect;
use App\Models\StudentLifeUniformBody;
use Livewire\Component;

class StudentLife extends Component
{
    public string $activeTab = 'clubs';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.website.student-life', [
            'clubs'         => StudentLifeClub::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'prefects'      => StudentLifePrefect::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'houses'        => StudentLifeHouse::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
            'uniformBodies' => StudentLifeUniformBody::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.web');
    }
}
