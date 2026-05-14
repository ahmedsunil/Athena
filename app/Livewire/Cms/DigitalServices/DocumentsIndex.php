<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceDocument;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentsIndex extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $category = 'Forms & Applications';
    public string $file_type = 'PDF';
    public string $file_size = '';
    public string $audience = 'All';
    public string $published_at = '';
    public $file = null;
    public ?string $existing_file = null;
    public bool $fileRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    public array $categories = [
        'Forms & Applications',
        'Policies & Handbooks',
        'Timetables & Schedules',
        'Academic Resources',
    ];

    public array $fileTypes = ['PDF', 'DOCX', 'XLS', 'XLSX'];
    public array $audiences = ['All', 'Students', 'Parents', 'Staff'];

    protected function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['required', 'in:Forms & Applications,Policies & Handbooks,Timetables & Schedules,Academic Resources'],
            'file_type'    => ['required', 'in:PDF,DOCX,XLS,XLSX'],
            'file_size'    => ['nullable', 'string', 'max:50'],
            'audience'     => ['required', 'in:All,Students,Parents,Staff'],
            'published_at' => ['required', 'date'],
            'file'         => ['nullable', 'file', 'max:10240'],
            'sort_order'   => ['integer', 'min:0'],
            'is_active'    => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => $this->title,
            'category'     => $this->category,
            'file_type'    => $this->file_type,
            'file_size'    => $this->file_size ?: null,
            'audience'     => $this->audience,
            'published_at' => $this->published_at,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
        ];

        if ($this->file) {
            $data['file_path'] = $this->file->store('digital-services/documents', 'public');
        } elseif ($this->fileRemoved) {
            $data['file_path'] = null;
        }

        if ($this->editingId) {
            DigitalServiceDocument::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Document updated.');
        } else {
            DigitalServiceDocument::create($data);
            $this->dispatch('toast', message: 'Document added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $doc = DigitalServiceDocument::findOrFail($id);
        $this->editingId    = $doc->id;
        $this->title        = $doc->title;
        $this->category     = $doc->category;
        $this->file_type    = $doc->file_type;
        $this->file_size    = $doc->file_size ?? '';
        $this->audience     = $doc->audience;
        $this->published_at = $doc->published_at->format('Y-m-d');
        $this->existing_file = $doc->file_path;
        $this->fileRemoved  = false;
        $this->sort_order   = $doc->sort_order;
        $this->is_active    = $doc->is_active;
    }

    public function delete(int $id): void
    {
        DigitalServiceDocument::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Document deleted.');
    }

    public function removeFile(): void
    {
        $this->file          = null;
        $this->existing_file = null;
        $this->fileRemoved   = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'file_size', 'published_at', 'file', 'existing_file', 'fileRemoved', 'editingId']);
        $this->category  = 'Forms & Applications';
        $this->file_type = 'PDF';
        $this->audience  = 'All';
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.digital-services.documents-index', [
            'documents' => DigitalServiceDocument::orderBy('sort_order')->orderBy('published_at', 'desc')->orderBy('id', 'desc')->get(),
        ])->layout('layouts.app', ['title' => 'Digital Services — Documents']);
    }
}
