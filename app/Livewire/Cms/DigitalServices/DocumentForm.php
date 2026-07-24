<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceDocument;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentForm extends Component
{
    use WithFileUploads;

    public ?int $itemId = null;

    public string $title_en = '';
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

    public array $categories = [
        'Forms & Applications',
        'Policies & Handbooks',
        'Timetables & Schedules',
        'Academic Resources',
    ];

    public array $fileTypes = ['PDF', 'DOCX', 'XLS', 'XLSX'];
    public array $audiences = ['All', 'Students', 'Parents', 'Staff'];

    public function mount(?int $itemId = null): void
    {
        if ($itemId) {
            $doc = DigitalServiceDocument::findOrFail($itemId);
            $this->itemId        = $doc->id;
            $this->title_en      = $doc->getTranslation('title', 'en', false) ?? '';
            $this->category      = $doc->category;
            $this->file_type     = $doc->file_type;
            $this->file_size     = $doc->file_size ?? '';
            $this->audience      = $doc->audience;
            $this->published_at  = $doc->published_at->format('Y-m-d');
            $this->existing_file = $doc->file_path;
            $this->sort_order    = $doc->sort_order;
            $this->is_active     = $doc->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'     => ['required', 'string', 'max:255'],
            'category'     => ['required', 'in:Forms & Applications,Policies & Handbooks,Timetables & Schedules,Academic Resources'],
            'file_type'    => ['required', 'in:PDF,DOCX,XLS,XLSX'],
            'file_size'    => ['nullable', 'string', 'max:50'],
            'audience'     => ['required', 'in:All,Students,Parents,Staff'],
            'published_at' => ['required', 'date'],
            'file'         => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:10240'],
            'sort_order'   => ['integer', 'min:0'],
            'is_active'    => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'        => ['en' => $this->title_en],
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

        if ($this->itemId) {
            DigitalServiceDocument::findOrFail($this->itemId)->update($data);
            $this->dispatch('toast', message: 'Document updated.');
        } else {
            DigitalServiceDocument::create($data);
            $this->dispatch('toast', message: 'Document added.');
        }

        $this->redirect(route('cms.digital-services.documents'), navigate: true);
    }

    public function removeFile(): void
    {
        $this->file          = null;
        $this->existing_file = null;
        $this->fileRemoved   = true;
    }

    public function render()
    {
        return view('livewire.cms.digital-services.document-form')
            ->layout('layouts.app', ['title' => $this->itemId ? 'Edit Document' : 'New Document']);
    }
}
