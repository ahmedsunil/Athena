<?php

namespace App\Livewire\Cms\Announcements;

use App\Models\Announcement;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class AnnouncementForm extends Component
{
    use WithFileUploads;

    public ?int $announcementId = null;

    public string $icon_key = 'Bell';
    public string $category = 'Announcement';
    public string $title_en = '';
    public string $slug = '';
    public string $description_en = '';
    public string $deadline = '';
    public array $attachments = [];
    public array $uploaded_files = [];
    public bool $is_active = true;
    public int $sort_order = 0;

    public function mount(?int $announcementId = null): void
    {
        if ($announcementId) {
            $announcement = Announcement::findOrFail($announcementId);
            $this->announcementId   = $announcement->id;
            $this->icon_key         = $announcement->icon_key;
            $this->category         = $announcement->category;
            $this->title_en         = $announcement->getTranslation('title', 'en', false) ?? '';
            $this->slug             = $announcement->slug;
            $this->description_en   = $announcement->getTranslation('description', 'en', false) ?? '';
            $this->deadline         = $announcement->deadline?->format('Y-m-d') ?? '';
            $this->attachments      = $announcement->attachments ?? [];
            $this->uploaded_files   = [];
            $this->is_active        = $announcement->is_active;
            $this->sort_order       = $announcement->sort_order;
        }
    }

    public function updatedTitleEn(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    protected function rules(): array
    {
        return [
            'icon_key'        => ['required', 'string', 'max:50'],
            'category'        => ['required', 'string', 'max:80'],
            'title_en'        => ['required', 'string', 'max:255'],
            'slug'            => ['required', 'string', 'max:255', 'unique:announcements,slug,' . ($this->announcementId ?? 'NULL')],
            'description_en'  => ['nullable', 'string'],
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
            'title'       => ['en' => $this->title_en],
            'slug'        => Str::slug($this->slug) ?: Str::slug($this->title_en),
            'description' => $this->description_en ? ['en' => $this->description_en] : null,
            'deadline'    => $this->deadline ?: null,
            'attachments' => $attachments ?: null,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
        ];

        if ($this->announcementId) {
            Announcement::findOrFail($this->announcementId)->update($data);
            $this->dispatch('toast', message: 'Announcement updated.');
        } else {
            Announcement::create($data);
            $this->dispatch('toast', message: 'Announcement created.');
        }

        $this->redirect(route('cms.announcements.index'), navigate: true);
    }

    public function removeAttachment(int $index): void
    {
        array_splice($this->attachments, $index, 1);
    }

    public function addUrlAttachment(): void
    {
        $this->attachments[] = ['label' => '', 'url' => ''];
    }

    public function render()
    {
        $title = $this->announcementId ? 'Edit announcement' : 'New announcement';
        return view('livewire.cms.announcements.announcement-form', [
            'iconKeys' => config('icons'),
        ])->layout('layouts.app', ['title' => $title]);
    }
}
