<?php

namespace App\Livewire\Cms\About;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\WithFileUploads;

class AchievementsIndex extends Component
{
    use WithFileUploads;

    public string $title_en = '';
    public string $title_dv = '';
    public string $category = 'school';
    public string $year = '';
    public string $description_en = '';
    public string $description_dv = '';
    public string $award_en = '';
    public string $award_dv = '';
    public string $event_name_en = '';
    public string $event_name_dv = '';
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
            'title_en'       => ['required', 'string', 'max:255'],
            'title_dv'       => ['nullable', 'string', 'max:255'],
            'category'       => ['required', 'in:school,students,staff'],
            'year'           => ['required', 'integer', 'min:1900', 'max:2100'],
            'description_en' => ['nullable', 'string'],
            'description_dv' => ['nullable', 'string'],
            'award_en'       => ['nullable', 'string', 'max:255'],
            'award_dv'       => ['nullable', 'string', 'max:255'],
            'event_name_en'  => ['nullable', 'string', 'max:255'],
            'event_name_dv'  => ['nullable', 'string', 'max:255'],
            'person_name'    => ['nullable', 'string', 'max:255'],
            'is_active'      => ['boolean'],
            'sort_order'     => ['integer', 'min:0'],
            'photo'          => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'       => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'category'    => $this->category,
            'year'        => (int) $this->year,
            'description' => ['en' => $this->description_en, 'dv' => $this->description_dv],
            'award'       => ['en' => $this->award_en, 'dv' => $this->award_dv],
            'event_name'  => ['en' => $this->event_name_en, 'dv' => $this->event_name_dv],
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
        $this->title_en       = $item->getTranslation('title', 'en', false) ?? '';
        $this->title_dv       = $item->getTranslation('title', 'dv', false) ?? '';
        $this->category       = $item->category;
        $this->year           = (string) $item->year;
        $this->description_en = $item->getTranslation('description', 'en', false) ?? '';
        $this->description_dv = $item->getTranslation('description', 'dv', false) ?? '';
        $this->award_en       = $item->getTranslation('award', 'en', false) ?? '';
        $this->award_dv       = $item->getTranslation('award', 'dv', false) ?? '';
        $this->event_name_en  = $item->getTranslation('event_name', 'en', false) ?? '';
        $this->event_name_dv  = $item->getTranslation('event_name', 'dv', false) ?? '';
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
        $this->reset([
            'title_en', 'title_dv', 'category', 'year',
            'description_en', 'description_dv',
            'award_en', 'award_dv', 'event_name_en', 'event_name_dv',
            'person_name', 'is_active', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId',
        ]);
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
