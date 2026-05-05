<?php

namespace App\Livewire\Cms\Links;

use Livewire\Component;

class LinksIndex extends Component
{
    public function render()
    {
        return view('livewire.cms.links.links-index', [
            'links' => config('links'),
        ])->layout('layouts.app', ['title' => 'Links']);
    }
}
