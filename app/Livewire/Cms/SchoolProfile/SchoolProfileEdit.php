<?php

namespace App\Livewire\Cms\SchoolProfile;

use App\Models\SchoolProfile;
use Livewire\Component;
use Livewire\WithFileUploads;

class SchoolProfileEdit extends Component
{
    use WithFileUploads;

    public string $school_name = '';
    public string $motto = '';
    public string $short_description = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $island = '';
    public string $atoll = '';
    public string $country = '';
    public string $principal_name = '';
    public string $principal_designation = '';
    public string $principal_message = '';
    public ?string $existing_logo = null;
    public ?string $existing_principal_photo = null;
    public $logo = null;
    public $principal_photo = null;

    public function mount(): void
    {
        $profile = SchoolProfile::singleton();
        $this->school_name          = $profile->school_name ?? '';
        $this->motto                = $profile->motto ?? '';
        $this->short_description    = $profile->short_description ?? '';
        $this->email                = $profile->email ?? '';
        $this->phone                = $profile->phone ?? '';
        $this->address              = $profile->address ?? '';
        $this->island               = $profile->island ?? '';
        $this->atoll                = $profile->atoll ?? '';
        $this->country              = $profile->country ?? '';
        $this->principal_name       = $profile->principal_name ?? '';
        $this->principal_designation = $profile->principal_designation ?? '';
        $this->principal_message    = $profile->principal_message ?? '';
        $this->existing_logo        = $profile->logo_path;
        $this->existing_principal_photo = $profile->principal_photo_path;
    }

    protected function rules(): array
    {
        return [
            'school_name'           => ['nullable', 'string', 'max:255'],
            'motto'                 => ['nullable', 'string', 'max:255'],
            'short_description'     => ['nullable', 'string'],
            'email'                 => ['nullable', 'email', 'max:255'],
            'phone'                 => ['nullable', 'string', 'max:50'],
            'address'               => ['nullable', 'string', 'max:500'],
            'island'                => ['nullable', 'string', 'max:100'],
            'atoll'                 => ['nullable', 'string', 'max:100'],
            'country'               => ['nullable', 'string', 'max:100'],
            'principal_name'        => ['nullable', 'string', 'max:255'],
            'principal_designation' => ['nullable', 'string', 'max:255'],
            'principal_message'     => ['nullable', 'string'],
            'logo'                  => ['nullable', 'image', 'max:2048'],
            'principal_photo'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $profile = SchoolProfile::singleton();
        $data = [
            'school_name'           => $this->school_name,
            'motto'                 => $this->motto,
            'short_description'     => $this->short_description,
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'address'               => $this->address,
            'island'                => $this->island,
            'atoll'                 => $this->atoll,
            'country'               => $this->country,
            'principal_name'        => $this->principal_name,
            'principal_designation' => $this->principal_designation,
            'principal_message'     => $this->principal_message,
        ];

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('school', 'public');
        }

        if ($this->principal_photo) {
            $data['principal_photo_path'] = $this->principal_photo->store('school', 'public');
        }

        $profile->update($data);

        $this->dispatch('toast', message: 'School profile saved.');
    }

    public function render()
    {
        return view('livewire.cms.school-profile.school-profile-edit')
            ->layout('layouts.app', ['title' => 'School Profile']);
    }
}
