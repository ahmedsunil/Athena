<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicLevel;
use Livewire\Component;
use Livewire\WithFileUploads;

class AcademicLevelsIndex extends Component
{
    use WithFileUploads;

    public string $abbreviation = '';
    public string $label_en = '';
    public string $label_dv = '';
    public string $age_range_en = '';
    public string $age_range_dv = '';
    public string $year_groups_en = '';
    public string $year_groups_dv = '';
    public string $lead_teacher = '';
    public $lead_teacher_photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public array $subjects = [];
    public array $targets = [];
    public array $streams = [];
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'abbreviation'       => ['required', 'string', 'max:10'],
            'label_en'           => ['required', 'string', 'max:255'],
            'label_dv'           => ['nullable', 'string', 'max:255'],
            'age_range_en'       => ['required', 'string', 'max:100'],
            'age_range_dv'       => ['nullable', 'string', 'max:100'],
            'year_groups_en'     => ['required', 'string', 'max:255'],
            'year_groups_dv'     => ['nullable', 'string', 'max:255'],
            'lead_teacher'       => ['required', 'string', 'max:255'],
            'lead_teacher_photo' => ['nullable', 'image', 'max:2048'],
            'subjects'           => ['nullable', 'array'],
            'subjects.*'         => ['nullable', 'string', 'max:255'],
            'targets'            => ['nullable', 'array'],
            'targets.*'          => ['nullable', 'string', 'max:255'],
            'streams'            => ['nullable', 'array'],
            'streams.*'          => ['nullable', 'string', 'max:255'],
            'sort_order'         => ['integer', 'min:0'],
            'is_active'          => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'abbreviation' => strtoupper(trim($this->abbreviation)),
            'label'        => ['en' => $this->label_en, 'dv' => $this->label_dv],
            'age_range'    => ['en' => $this->age_range_en, 'dv' => $this->age_range_dv],
            'year_groups'  => ['en' => $this->year_groups_en, 'dv' => $this->year_groups_dv],
            'lead_teacher' => $this->lead_teacher,
            'subjects'     => array_values(array_filter($this->subjects, fn ($s) => trim($s) !== '')) ?: null,
            'targets'      => array_values(array_filter($this->targets,  fn ($t) => trim($t) !== '')) ?: null,
            'streams'      => array_values(array_filter($this->streams,  fn ($s) => trim($s) !== '')) ?: null,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
        ];

        if ($this->lead_teacher_photo) {
            $data['lead_teacher_photo_path'] = $this->lead_teacher_photo->store('academics', 'public');
        } elseif ($this->photoRemoved) {
            $data['lead_teacher_photo_path'] = null;
        }

        if ($this->editingId) {
            AcademicLevel::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Level updated.');
        } else {
            AcademicLevel::create($data);
            $this->dispatch('toast', message: 'Level added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $level = AcademicLevel::findOrFail($id);
        $this->editingId      = $level->id;
        $this->abbreviation   = $level->abbreviation;
        $this->label_en       = $level->getTranslation('label', 'en', false) ?? '';
        $this->label_dv       = $level->getTranslation('label', 'dv', false) ?? '';
        $this->age_range_en   = $level->getTranslation('age_range', 'en', false) ?? '';
        $this->age_range_dv   = $level->getTranslation('age_range', 'dv', false) ?? '';
        $this->year_groups_en = $level->getTranslation('year_groups', 'en', false) ?? '';
        $this->year_groups_dv = $level->getTranslation('year_groups', 'dv', false) ?? '';
        $this->lead_teacher   = $level->lead_teacher;
        $this->existing_photo = $level->lead_teacher_photo_path;
        $this->photoRemoved   = false;
        $this->subjects       = $level->subjects ?? [];
        $this->targets        = $level->targets ?? [];
        $this->streams        = $level->streams ?? [];
        $this->sort_order     = $level->sort_order;
        $this->is_active      = $level->is_active;
    }

    public function delete(int $id): void
    {
        AcademicLevel::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Level deleted.');
    }

    public function removePhoto(): void
    {
        $this->lead_teacher_photo = null;
        $this->existing_photo     = null;
        $this->photoRemoved       = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function addSubject(): void              { $this->subjects[] = ''; }
    public function removeSubject(int $i): void    { array_splice($this->subjects, $i, 1); }

    public function addTarget(): void              { $this->targets[] = ''; }
    public function removeTarget(int $i): void     { array_splice($this->targets, $i, 1); }

    public function addStream(): void              { $this->streams[] = ''; }
    public function removeStream(int $i): void     { array_splice($this->streams, $i, 1); }

    private function resetForm(): void
    {
        $this->reset([
            'abbreviation', 'label_en', 'label_dv',
            'age_range_en', 'age_range_dv', 'year_groups_en', 'year_groups_dv',
            'lead_teacher', 'lead_teacher_photo', 'existing_photo', 'photoRemoved',
            'subjects', 'targets', 'streams', 'sort_order', 'editingId',
        ]);
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.academics.levels-index', [
            'levels' => AcademicLevel::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Academics — Levels']);
    }
}
