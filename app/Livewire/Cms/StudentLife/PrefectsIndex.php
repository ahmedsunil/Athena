<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifePrefect;
use Livewire\Component;
use Livewire\WithFileUploads;

class PrefectsIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $role = '';
    public string $class_name = '';
    public string $quote = '';
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'role'       => ['required', 'string', 'max:100'],
            'class_name' => ['nullable', 'string', 'max:100'],
            'quote'      => ['nullable', 'string'],
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
            'role'       => $this->role,
            'class_name' => $this->class_name ?: null,
            'quote'      => $this->quote ?: null,
            'sort_order' => $this->sort_order,
            'is_active'  => $this->is_active,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('student-life/prefects', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            StudentLifePrefect::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Prefect updated.');
        } else {
            StudentLifePrefect::create($data);
            $this->dispatch('toast', message: 'Prefect added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $prefect = StudentLifePrefect::findOrFail($id);
        $this->editingId      = $prefect->id;
        $this->name           = $prefect->name;
        $this->role           = $prefect->role;
        $this->class_name     = $prefect->class_name ?? '';
        $this->quote          = $prefect->quote ?? '';
        $this->existing_photo = $prefect->photo_path;
        $this->photoRemoved   = false;
        $this->sort_order     = $prefect->sort_order;
        $this->is_active      = $prefect->is_active;
    }

    public function delete(int $id): void
    {
        StudentLifePrefect::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Prefect deleted.');
    }

    public function removePhoto(): void
    {
        $this->photo          = null;
        $this->existing_photo = null;
        $this->photoRemoved   = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'role', 'class_name', 'quote',
            'photo', 'existing_photo', 'photoRemoved', 'editingId',
        ]);
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.student-life.prefects-index', [
            'prefects' => StudentLifePrefect::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Student Life — Prefects']);
    }
}
