<?php

namespace App\Livewire\Cms\About;

use App\Models\StaffMember;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffForm extends Component
{
    use WithFileUploads;

    public ?int $staffId = null;

    public string $name = '';
    public string $designation_en = '';
    public string $education_en = '';
    public string $section = 'academic';
    public string $subSection = '';
    public int $sortOrder = 0;
    public bool $isActive = true;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public array $workExperiences = [];

    public function mount(?int $staffId = null): void
    {
        if ($staffId) {
            $item = StaffMember::findOrFail($staffId);
            $this->staffId            = $item->id;
            $this->name               = $item->name;
            $this->designation_en     = $item->getTranslation('designation', 'en', false) ?? '';
            $this->education_en       = $item->getTranslation('education', 'en', false) ?? '';
            $this->section            = $item->section;
            $this->subSection         = $item->sub_section ?? '';
            $this->sortOrder          = $item->sort_order;
            $this->isActive           = $item->is_active;
            $this->existing_photo     = $item->photo_path;
            $this->workExperiences    = $item->work_experiences ?? [];
        }
    }

    protected function rules(): array
    {
        return [
            'name'                          => ['required', 'string', 'max:255'],
            'designation_en'                => ['required', 'string', 'max:255'],
            'education_en'                  => ['nullable', 'string', 'max:255'],
            'section'                       => ['required', 'in:senior_management,academic,administrative'],
            'subSection'                    => ['nullable', 'string', 'max:50'],
            'sortOrder'                     => ['integer', 'min:0'],
            'isActive'                      => ['boolean'],
            'photo'                         => ['nullable', 'image', 'max:2048'],
            'workExperiences'               => ['array'],
            'workExperiences.*.title'        => ['required', 'string', 'max:255'],
            'workExperiences.*.institution'  => ['required', 'string', 'max:255'],
            'workExperiences.*.period'       => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'             => $this->name,
            'designation'      => ['en' => $this->designation_en],
            'education'        => $this->education_en ? ['en' => $this->education_en] : null,
            'section'          => $this->section,
            'sub_section'      => $this->section === 'senior_management' ? null : ($this->subSection ?: null),
            'sort_order'       => $this->sortOrder,
            'is_active'        => $this->isActive,
            'work_experiences' => array_values($this->workExperiences),
        ];

        if ($this->photo) {
            if ($this->existing_photo) {
                Storage::disk('public')->delete($this->existing_photo);
            }
            $data['photo_path'] = $this->photo->store('staff-photos', 'public');
        } elseif ($this->photoRemoved) {
            if ($this->existing_photo) {
                Storage::disk('public')->delete($this->existing_photo);
            }
            $data['photo_path'] = null;
        }

        if ($this->staffId) {
            StaffMember::findOrFail($this->staffId)->update($data);
            $this->dispatch('toast', message: 'Staff member updated.');
        } else {
            StaffMember::create($data);
            $this->dispatch('toast', message: 'Staff member added.');
        }

        $this->redirect(route('cms.team'), navigate: true);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function addExperience(): void
    {
        $this->workExperiences[] = [
            'title' => '', 'institution' => '', 'period' => '',
        ];
    }

    public function removeExperience(int $index): void
    {
        array_splice($this->workExperiences, $index, 1);
        $this->workExperiences = array_values($this->workExperiences);
    }

    public function updatedSection(): void
    {
        $this->subSection = '';
    }

    public function render()
    {
        $title = $this->staffId ? 'Edit Staff Member' : 'New Staff Member';
        return view('livewire.cms.about.staff-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}
