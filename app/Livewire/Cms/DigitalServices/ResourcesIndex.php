<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceResource;
use Livewire\Component;
use Livewire\WithPagination;

class ResourcesIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        DigitalServiceResource::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Resource deleted.');
    }

    public function render()
    {
        return view('livewire.cms.digital-services.resources-index', [
            'resources' => DigitalServiceResource::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Media — Resources']);
    }
}
