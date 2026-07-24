<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicLevel;
use Livewire\Component;
use Livewire\WithFileUploads;

class AcademicLevelForm extends Component
{
    use WithFileUploads;

    public ?int $levelId = null;

    public string $abbreviation = '';
    public string $label_en = '';
    public string $age_range_en = '';
    public string $year_groups_en = '';
    public string $lead_teacher = '';
    public $lead_teacher_photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public array $subjects = [];
    public array $targets = [];
    public array $streams = [];
    public int $sort_order = 0;
    public bool $is_active = true;

    public function mount(?int $levelId = null): void
    {
        if ($levelId) {
            $level = AcademicLevel::findOrFail($levelId);
            $this->levelId        = $level->id;
            $this->abbreviation   = $level->abbreviation;
            $this->label_en       = $level->getTranslation('label', 'en', false) ?? '';
            $this->age_range_en   = $level->getTranslation('age_range', 'en', false) ?? '';
            $this->year_groups_en = $level->getTranslation('year_groups', 'en', false) ?? '';
            $this->lead_teacher   = $level->lead_teacher;
            $this->existing_photo = $level->lead_teacher_photo_path;
            $this->photoRemoved   = false;
            $this->subjects       = $level->subjects ?? [];
            $this->targets        = $level->targets ?? [];
            $this->streams        = $level->streams ?? [];
            $this->sort_order     = $level->sort_order;
            $this->is_active      = $level->is_active;
        }
    }

    protected function rules(): array
    {
        return [
            'abbreviation'       => ['required', 'string', 'max:10'],
            'label_en'           => ['required', 'string', 'max:255'],
            'age_range_en'       => ['required', 'string', 'max:100'],
            'year_groups_en'     => ['required', 'string', 'max:255'],
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
            'label'        => ['en' => $this->label_en],
            'age_range'    => ['en' => $this->age_range_en],
            'year_groups'  => ['en' => $this->year_groups_en],
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

        if ($this->levelId) {
            AcademicLevel::findOrFail($this->levelId)->update($data);
            $this->dispatch('toast', message: 'Level updated.');
        } else {
            AcademicLevel::create($data);
            $this->dispatch('toast', message: 'Level added.');
        }

        $this->redirect(route('cms.academics.levels'), navigate: true);
    }

    public function removePhoto(): void
    {
        $this->lead_teacher_photo = null;
        $this->existing_photo     = null;
        $this->photoRemoved       = true;
    }

    public function addSubject(): void              { $this->subjects[] = ''; }
    public function removeSubject(int $i): void     { array_splice($this->subjects, $i, 1); }

    public function addTarget(): void              { $this->targets[] = ''; }
    public function removeTarget(int $i): void     { array_splice($this->targets, $i, 1); }

    public function addStream(): void              { $this->streams[] = ''; }
    public function removeStream(int $i): void     { array_splice($this->streams, $i, 1); }

    public function render()
    {
        $title = $this->levelId ? 'Edit level' : 'Add level';
        return view('livewire.cms.academics.academic-level-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
