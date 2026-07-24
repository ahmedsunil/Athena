<?php

namespace App\Livewire\Cms\About;

use App\Models\FoundingMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoundingMemberForm extends Component
{
    use WithFileUploads;

    public ?int $foundingId = null;

    public string $name = '';
    public string $subject_en = '';
    public string $tribute_en = '';
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;

    public function mount(?int $foundingId = null): void
    {
        if ($foundingId) {
            $item = FoundingMember::findOrFail($foundingId);
            $this->foundingId     = $item->id;
            $this->name           = $item->name;
            $this->subject_en     = $item->getTranslation('subject', 'en', false) ?? '';
            $this->tribute_en     = $item->getTranslation('tribute', 'en', false) ?? '';
            $this->sort_order     = $item->sort_order;
            $this->existing_photo = $item->photo_path;
        }
    }

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'subject_en' => ['nullable', 'string', 'max:255'],
            'tribute_en' => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'subject'    => ['en' => $this->subject_en],
            'tribute'    => ['en' => $this->tribute_en],
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/founding', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->foundingId) {
            FoundingMember::findOrFail($this->foundingId)->update($data);
            $this->dispatch('toast', message: 'Founding member updated.');
        } else {
            FoundingMember::create($data);
            $this->dispatch('toast', message: 'Founding member added.');
        }

        $this->redirect(route('cms.about.founding-members'), navigate: true);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function render()
    {
        $title = $this->foundingId ? 'Edit Founding Member' : 'New Founding Member';
        return view('livewire.cms.about.founding-member-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
