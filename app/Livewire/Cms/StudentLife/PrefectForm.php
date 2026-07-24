<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifePrefect;
use Livewire\Component;
use Livewire\WithFileUploads;

class PrefectForm extends Component
{
    use WithFileUploads;

    public ?int $prefectId = null;

    public string $name = '';
    public string $role_en = '';
    public string $class_name = '';
    public string $quote_en = '';
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $prefectId = null): void
    {
        if ($prefectId) {
            $prefect = StudentLifePrefect::findOrFail($prefectId);
            $this->prefectId       = $prefect->id;
            $this->name            = $prefect->name;
            $this->role_en         = $prefect->getTranslation('role', 'en', false) ?? '';
            $this->class_name      = $prefect->class_name ?? '';
            $this->quote_en        = $prefect->getTranslation('quote', 'en', false) ?? '';
            $this->existing_photo  = $prefect->photo_path;
            $this->sort_order      = $prefect->sort_order;
            $this->is_active       = $prefect->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'role_en'    => ['required', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:100'],
            'quote_en'   => ['nullable', 'string'],
            'photo'      => ['nullable', 'image', 'max:2048'],
            'sort_order' => ['integer', 'min:0'],
            'is_active'  => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'role'       => ['en' => $this->role_en],
            'class_name' => $this->class_name ?: null,
            'quote'      => $this->quote_en ? ['en' => $this->quote_en] : null,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('student-life/prefects', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->prefectId) {
            StudentLifePrefect::findOrFail($this->prefectId)->update($data);
            $this->dispatch('toast', message: 'Prefect updated.');
        } else {
            StudentLifePrefect::create($data);
            $this->dispatch('toast', message: 'Prefect added.');
        }

        $this->redirect(route('cms.prefects'), navigate: true);
    }

    public function removePhoto(): void
    {
        $this->photo          = null;
        $this->existing_photo = null;
        $this->photoRemoved   = true;
    }

    public function render()
    {
        return view('livewire.cms.student-life.prefect-form')
            ->layout('layouts.app', ['title' => $this->prefectId ? 'Edit Prefect' : 'New Prefect']);
    }
}
