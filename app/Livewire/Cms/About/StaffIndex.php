<?php

namespace App\Livewire\Cms\About;

use App\Models\StaffMember;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffIndex extends Component
{
    use WithFileUploads;

    public string  $name        = '';
    public string  $designation_en = '';
    public string  $designation_dv = '';
    public string  $education_en   = '';
    public string  $education_dv   = '';
    public string  $section     = 'academic';
    public string  $subSection  = '';
    public int     $sortOrder   = 0;
    public bool    $isActive    = true;
    public         $photo       = null;
    public ?string $existing_photo = null;
    public bool    $photoRemoved = false;
    public ?int    $editingId   = null;
    public array   $workExperiences = [];

    protected function rules(): array
    {
        return [
            'name'                          => ['required', 'string', 'max:255'],
            'designation_en'                => ['required', 'string', 'max:255'],
            'designation_dv'                => ['nullable', 'string', 'max:255'],
            'education_en'                  => ['nullable', 'string', 'max:255'],
            'education_dv'                  => ['nullable', 'string', 'max:255'],
            'section'                       => ['required', 'in:senior_management,academic,administrative'],
            'subSection'                    => ['nullable', 'string', 'max:50'],
            'sortOrder'                     => ['integer', 'min:0'],
            'isActive'                      => ['boolean'],
            'photo'                         => ['nullable', 'image', 'max:2048'],
            'workExperiences'               => ['array'],
            'workExperiences.*.title'       => ['required', 'string', 'max:255'],
            'workExperiences.*.institution' => ['required', 'string', 'max:255'],
            'workExperiences.*.period'      => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'             => $this->name,
            'designation'      => ['en' => $this->designation_en, 'dv' => $this->designation_dv],
            'education'        => ($this->education_en || $this->education_dv)
                                    ? ['en' => $this->education_en, 'dv' => $this->education_dv]
                                    : null,
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

        if ($this->editingId) {
            StaffMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Staff member updated.');
        } else {
            StaffMember::create($data);
            $this->dispatch('toast', message: 'Staff member added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = StaffMember::findOrFail($id);
        $this->editingId         = $item->id;
        $this->name              = $item->name;
        $this->designation_en    = $item->getTranslation('designation', 'en', false) ?? '';
        $this->designation_dv    = $item->getTranslation('designation', 'dv', false) ?? '';
        $this->education_en      = $item->getTranslation('education', 'en', false) ?? '';
        $this->education_dv      = $item->getTranslation('education', 'dv', false) ?? '';
        $this->section           = $item->section;
        $this->subSection        = $item->sub_section ?? '';
        $this->sortOrder         = $item->sort_order;
        $this->isActive          = $item->is_active;
        $this->existing_photo = $item->photo_path;
        $this->photoRemoved      = false;
        $this->photo             = null;
        $this->workExperiences   = $item->work_experiences ?? [];
    }

    public function delete(int $id): void
    {
        $item = StaffMember::findOrFail($id);
        if ($item->photo_path) {
            Storage::disk('public')->delete($item->photo_path);
        }
        $item->delete();
        $this->dispatch('toast', message: 'Staff member deleted.');
    }

    public function removePhoto(): void
    {
        $this->photo             = null;
        $this->existing_photo = null;
        $this->photoRemoved      = true;
    }

    public function addExperience(): void
    {
        $this->workExperiences[] = ['title' => '', 'institution' => '', 'period' => ''];
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

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'designation_en', 'designation_dv', 'education_en', 'education_dv',
            'subSection', 'sortOrder', 'photo', 'existing_photo', 'photoRemoved',
            'editingId', 'workExperiences',
        ]);
        $this->section   = 'academic';
        $this->isActive  = true;
        $this->sortOrder = 0;
    }

    public function render()
    {
        return view('livewire.cms.about.staff-index', [
            'staffMembers' => StaffMember::orderByRaw("FIELD(section,'senior_management','academic','administrative')")
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Team']);
    }
}
