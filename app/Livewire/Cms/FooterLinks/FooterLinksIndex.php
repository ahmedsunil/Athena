<?php

namespace App\Livewire\Cms\FooterLinks;

use App\Models\FooterLink;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class FooterLinksIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        FooterLink::findOrFail($id)->delete();
        Cache::store('file')->forget('footer_data');
        $this->dispatch('toast', message: 'Footer link deleted.');
    }

    public function render()
    {
        return view('livewire.cms.footer-links.footer-links-index', [
            'links'    => FooterLink::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Footer Links']);
    }
}
