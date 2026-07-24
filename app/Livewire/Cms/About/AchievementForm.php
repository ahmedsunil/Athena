<?php

namespace App\Livewire\Cms\About;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\WithFileUploads;

class AchievementForm extends Component
{
    use WithFileUploads;

    public ?int $achievementId = null;

    public string $title_en = '';
    public string $category = 'school';
    public string $year = '';
    public string $description_en = '';
    public string $award_en = '';
    public string $event_name_en = '';
    public string $person_name = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;

    public function mount(?int $achievementId = null): void
    {
        if ($achievementId) {
            $item = Achievement::findOrFail($achievementId);
            $this->achievementId  = $item->id;
            $this->title_en       = $item->getTranslation('title', 'en', false) ?? '';
            $this->category       = $item->category;
            $this->year           = (string) $item->year;
            $this->description_en = $item->getTranslation('description', 'en', false) ?? '';
            $this->award_en       = $item->getTranslation('award', 'en', false) ?? '';
            $this->event_name_en  = $item->getTranslation('event_name', 'en', false) ?? '';
            $this->person_name    = $item->person_name ?? '';
            $this->is_active      = $item->is_active;
            $this->sort_order     = $item->sort_order;
            $this->existing_photo = $item->photo_path;
        } else {
            $this->year = (string) now()->year;
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'       => ['required', 'string', 'max:255'],
            'category'       => ['required', 'in:school,students,staff'],
            'year'           => ['required', 'integer', 'min:1900', 'max:2100'],
            'description_en' => ['nullable', 'string'],
            'award_en'       => ['nullable', 'string', 'max:255'],
            'event_name_en'  => ['nullable', 'string', 'max:255'],
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
            'title'       => ['en' => $this->title_en],
            'category'    => $this->category,
            'year'        => (int) $this->year,
            'description' => ['en' => $this->description_en],
            'award'       => ['en' => $this->award_en],
            'event_name'  => ['en' => $this->event_name_en],
            'person_name' => $this->person_name ?: null,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/achievements', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->achievementId) {
            Achievement::findOrFail($this->achievementId)->update($data);
            $this->dispatch('toast', message: 'Achievement updated.');
        } else {
            Achievement::create($data);
            $this->dispatch('toast', message: 'Achievement added.');
        }

        $this->redirect(route('cms.achievements'), navigate: true);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function render()
    {
        $title = $this->achievementId ? 'Edit Achievement' : 'New Achievement';
        return view('livewire.cms.about.achievement-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
