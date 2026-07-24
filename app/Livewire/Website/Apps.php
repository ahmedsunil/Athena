<?php

namespace App\Livewire\Website;

use App\Models\WebApp;
use Livewire\Component;

class Apps extends Component
{
    public function render()
    {
        return view('livewire.website.apps', [
            'apps' => WebApp::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }
}
