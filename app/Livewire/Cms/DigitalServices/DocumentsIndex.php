<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceDocument;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        DigitalServiceDocument::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Document deleted.');
    }

    public function render()
    {
        return view('livewire.cms.digital-services.documents-index', [
            'documents' => DigitalServiceDocument::orderBy('sort_order')->orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Media — Documents']);
    }
}
