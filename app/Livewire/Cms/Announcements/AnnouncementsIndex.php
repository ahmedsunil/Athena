<?php

namespace App\Livewire\Cms\Announcements;

use App\Models\Announcement;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AnnouncementsIndex extends Component
{
    use WithFileUploads;

    public string $icon_key = 'Bell';
    public string $category = 'Announcement';
    public string $title_en = '';
    public string $title_dv = '';
    public string $slug = '';
    public string $description_en = '';
    public string $description_dv = '';
    public string $deadline = '';
    public array $attachments = [];
    public array $uploaded_files = [];
    public bool $is_active = true;
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'icon_key'        => ['required', 'string', 'max:50'],
            'category'        => ['required', 'string', 'max:80'],
            'title_en'        => ['required', 'string', 'max:255'],
            'title_dv'        => ['nullable', 'string', 'max:255'],
            'slug'            => ['required', 'string', 'max:255', 'unique:announcements,slug,' . ($this->editingId ?? 'NULL')],
            'description_en'  => ['nullable', 'string'],
            'description_dv'  => ['nullable', 'string'],
            'deadline'        => ['nullable', 'date'],
            'attachments'     => ['nullable', 'array'],
            'attachments.*.label' => ['nullable', 'string', 'max:255'],
            'attachments.*.path' => ['nullable', 'string'],
            'attachments.*.url' => ['nullable', 'url:http,https'],
            'uploaded_files'  => ['nullable', 'array'],
            'uploaded_files.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
            'is_active'       => ['boolean'],
            'sort_order'      => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $attachments = collect($this->attachments)
            ->filter(fn ($attachment) => ! empty($attachment['label']) || ! empty($attachment['path']) || ! empty($attachment['url']))
            ->values()
            ->all();

        foreach ($this->uploaded_files as $file) {
            $attachments[] = [
                'label' => $file->getClientOriginalName(),
                'path' => $file->store('announcements', 'public'),
                'size' => $file->getSize(),
            ];
        }

        $data = [
            'icon_key'    => $this->icon_key,
            'category'    => $this->category,
            'title'       => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'slug'        => Str::slug($this->slug) ?: Str::slug($this->title_en),
            'description' => ($this->description_en || $this->description_dv)
                              ? ['en' => $this->description_en, 'dv' => $this->description_dv]
                              : null,
            'deadline'    => $this->deadline ?: null,
            'attachments' => $attachments ?: null,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
        ];

        if ($this->editingId) {
            Announcement::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Announcement updated.');
        } else {
            Announcement::create($data);
            $this->dispatch('toast', message: 'Announcement created.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $announcement = Announcement::findOrFail($id);

        $this->editingId       = $announcement->id;
        $this->icon_key        = $announcement->icon_key;
        $this->category        = $announcement->category;
        $this->title_en        = $announcement->getTranslation('title', 'en', false) ?? '';
        $this->title_dv        = $announcement->getTranslation('title', 'dv', false) ?? '';
        $this->slug            = $announcement->slug;
        $this->description_en  = $announcement->getTranslation('description', 'en', false) ?? '';
        $this->description_dv  = $announcement->getTranslation('description', 'dv', false) ?? '';
        $this->deadline        = $announcement->deadline?->format('Y-m-d') ?? '';
        $this->attachments     = $announcement->attachments ?? [];
        $this->uploaded_files  = [];
        $this->is_active       = $announcement->is_active;
        $this->sort_order      = $announcement->sort_order;
    }

    public function removeAttachment(int $index): void
    {
        array_splice($this->attachments, $index, 1);
    }

    public function addUrlAttachment(): void
    {
        $this->attachments[] = ['label' => '', 'url' => ''];
    }

    public function delete(int $id): void
    {
        Announcement::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Announcement deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'title_en',
            'title_dv',
            'slug',
            'description_en',
            'description_dv',
            'deadline',
            'attachments',
            'uploaded_files',
            'editingId',
        ]);
        $this->icon_key = 'Bell';
        $this->category = 'Announcement';
        $this->is_active = true;
        $this->sort_order = 0;
    }

    public function updatedTitleEn(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function render()
    {
        return view('livewire.cms.announcements.announcements-index', [
            'announcements' => Announcement::orderBy('sort_order')->orderByDesc('created_at')->get(),
            'iconKeys' => config('icons'),
        ])->layout('layouts.app', ['title' => 'Announcements']);
    }
}
