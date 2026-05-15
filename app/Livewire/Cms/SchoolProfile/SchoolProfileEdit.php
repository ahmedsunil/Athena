<?php

namespace App\Livewire\Cms\SchoolProfile;

use App\Models\SchoolProfile;
use Livewire\Component;
use Livewire\WithFileUploads;

class SchoolProfileEdit extends Component
{
    use WithFileUploads;

    public string $school_name_en = '';
    public string $school_name_dv = '';
    public string $motto_en = '';
    public string $motto_dv = '';
    public string $short_description_en = '';
    public string $short_description_dv = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public string $school_island = '';
    public string $atoll = '';
    public string $country = '';
    public string $principal_name = '';
    public string $principal_designation_en = '';
    public string $principal_designation_dv = '';
    public string $principal_message_en = '';
    public string $principal_message_dv = '';
    public ?string $existing_logo = null;
    public ?string $existing_principal_photo = null;
    public $logo = null;
    public $principal_photo = null;
    public bool $logoRemoved = false;
    public bool $principalPhotoRemoved = false;

    public function mount(): void
    {
        $profile = SchoolProfile::singleton();
        $this->school_name_en          = $profile->getTranslation('school_name', 'en', false) ?? '';
        $this->school_name_dv          = $profile->getTranslation('school_name', 'dv', false) ?? '';
        $this->motto_en                = $profile->getTranslation('motto', 'en', false) ?? '';
        $this->motto_dv                = $profile->getTranslation('motto', 'dv', false) ?? '';
        $this->short_description_en    = $profile->getTranslation('short_description', 'en', false) ?? '';
        $this->short_description_dv    = $profile->getTranslation('short_description', 'dv', false) ?? '';
        $this->email                   = $profile->email ?? '';
        $this->phone                   = $profile->phone ?? '';
        $this->address                 = $profile->address ?? '';
        $this->school_island           = $profile->island ?? '';
        $this->atoll                   = $profile->atoll ?? '';
        $this->country                 = $profile->country ?? '';
        $this->principal_name          = $profile->principal_name ?? '';
        $this->principal_designation_en = $profile->getTranslation('principal_designation', 'en', false) ?? '';
        $this->principal_designation_dv = $profile->getTranslation('principal_designation', 'dv', false) ?? '';
        $this->principal_message_en    = $profile->getTranslation('principal_message', 'en', false) ?? '';
        $this->principal_message_dv    = $profile->getTranslation('principal_message', 'dv', false) ?? '';
        $this->existing_logo           = $profile->logo_path;
        $this->existing_principal_photo = $profile->principal_photo_path;
    }

    protected function rules(): array
    {
        return [
            'school_name_en'           => ['nullable', 'string', 'max:255'],
            'school_name_dv'           => ['nullable', 'string', 'max:255'],
            'motto_en'                 => ['nullable', 'string', 'max:255'],
            'motto_dv'                 => ['nullable', 'string', 'max:255'],
            'short_description_en'     => ['nullable', 'string'],
            'short_description_dv'     => ['nullable', 'string'],
            'email'                    => ['nullable', 'email', 'max:255'],
            'phone'                    => ['nullable', 'string', 'max:50'],
            'address'                  => ['nullable', 'string', 'max:500'],
            'school_island'            => ['nullable', 'string', 'max:100'],
            'atoll'                    => ['nullable', 'string', 'max:100'],
            'country'                  => ['nullable', 'string', 'max:100'],
            'principal_name'           => ['nullable', 'string', 'max:255'],
            'principal_designation_en' => ['nullable', 'string', 'max:255'],
            'principal_designation_dv' => ['nullable', 'string', 'max:255'],
            'principal_message_en'     => ['nullable', 'string'],
            'principal_message_dv'     => ['nullable', 'string'],
            'logo'                     => ['nullable', 'image', 'max:2048'],
            'principal_photo'          => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function removeLogo(): void
    {
        $this->logo = null;
        $this->existing_logo = null;
        $this->logoRemoved = true;
    }

    public function removePrincipalPhoto(): void
    {
        $this->principal_photo = null;
        $this->existing_principal_photo = null;
        $this->principalPhotoRemoved = true;
    }

    public function save(): void
    {
        $this->validate();

        $profile = SchoolProfile::singleton();
        $data = [
            'school_name'           => ['en' => $this->school_name_en, 'dv' => $this->school_name_dv],
            'motto'                 => ['en' => $this->motto_en, 'dv' => $this->motto_dv],
            'short_description'     => ['en' => $this->short_description_en, 'dv' => $this->short_description_dv],
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'address'               => $this->address,
            'island'                => $this->school_island,
            'atoll'                 => $this->atoll,
            'country'               => $this->country,
            'principal_name'        => $this->principal_name,
            'principal_designation' => ['en' => $this->principal_designation_en, 'dv' => $this->principal_designation_dv],
            'principal_message'     => ['en' => $this->principal_message_en, 'dv' => $this->principal_message_dv],
        ];

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('school', 'public');
        } elseif ($this->logoRemoved) {
            $data['logo_path'] = null;
        }

        if ($this->principal_photo) {
            $data['principal_photo_path'] = $this->principal_photo->store('school', 'public');
        } elseif ($this->principalPhotoRemoved) {
            $data['principal_photo_path'] = null;
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
