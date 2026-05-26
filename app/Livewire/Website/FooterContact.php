<?php

namespace App\Livewire\Website;

use App\Models\SchoolProfile;
use Livewire\Component;

class FooterContact extends Component
{
    public function render()
    {
        return view('livewire.website.footer-contact', [
            'profile' => SchoolProfile::singleton(),
        ]);
    }
}
