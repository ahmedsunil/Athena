<?php

namespace App\Livewire\Cms\Apps;

use App\Models\WebApp;
use Livewire\Component;
use Livewire\WithPagination;

class WebAppsIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        WebApp::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'App deleted.');
    }

    public function render()
    {
        return view('livewire.cms.apps.web-apps-index', [
            'apps' => WebApp::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Apps']);
    }
}
