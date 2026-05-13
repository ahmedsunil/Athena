<?php

namespace App\Livewire\Cms\About;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\WithFileUploads;

class AchievementsIndex extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $category = 'school';
    public string $year = '';
    public string $description = '';
    public string $award = '';
    public string $event_name = '';
    public string $person_name = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'category'    => ['required', 'in:school,students,staff'],
            'year'        => ['required', 'integer', 'min:1900', 'max:2100'],
            'description' => ['nullable', 'string'],
            'award'       => ['nullable', 'string', 'max:255'],
            'event_name'  => ['nullable', 'string', 'max:255'],
            'person_name' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['integer', 'min:0'],
            'photo'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'       => $this->title,
            'category'    => $this->category,
            'year'        => (int) $this->year,
            'description' => $this->description,
            'award'       => $this->award,
            'event_name'  => $this->event_name,
            'person_name' => $this->person_name,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/achievements', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            Achievement::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Achievement updated.');
        } else {
            Achievement::create($data);
            $this->dispatch('toast', message: 'Achievement added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = Achievement::findOrFail($id);
        $this->editingId      = $item->id;
        $this->title          = $item->title;
        $this->category       = $item->category;
        $this->year           = (string) $item->year;
        $this->description    = $item->description ?? '';
        $this->award          = $item->award ?? '';
        $this->event_name     = $item->event_name ?? '';
        $this->person_name    = $item->person_name ?? '';
        $this->is_active      = $item->is_active;
        $this->sort_order     = $item->sort_order;
        $this->existing_photo = $item->photo_path;
        $this->photoRemoved   = false;
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Achievement::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Achievement deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'category', 'year', 'description', 'award',
            'event_name', 'person_name', 'is_active', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
        $this->category  = 'school';
        $this->is_active = true;
        $this->year      = (string) now()->year;
    }

    public function mount(): void
    {
        $this->year = (string) now()->year;
    }

    public function render()
    {
        return view('livewire.cms.about.achievements-index', [
            'achievements' => Achievement::orderByDesc('year')->orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Achievements']);
    }
}
