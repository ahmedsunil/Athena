<?php

namespace App\Livewire\Cms\SchoolProfile;

use App\Models\SchoolProfile;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithFileUploads;

class SchoolProfileEdit extends Component
{
    use WithFileUploads;

    public string $school_name_en = '';
    public string $motto_en = '';
    public string $short_description_en = '';
    public string $email = '';
    public string $phone = '';
    public string $address_en = '';
    public string $school_island_en = '';
    public string $atoll_en = '';
    public string $country_en = '';
    public string $principal_name_en = '';
    public string $principal_designation_en = '';
    public string $principal_message_en = '';
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
        $this->motto_en                = $profile->getTranslation('motto', 'en', false) ?? '';
        $this->short_description_en    = $profile->getTranslation('short_description', 'en', false) ?? '';
        $this->email                   = $profile->email ?? '';
        $this->phone                   = $profile->phone ?? '';
        $this->address_en              = $profile->getTranslation('address', 'en', false) ?? '';
        $this->school_island_en        = $profile->getTranslation('island', 'en', false) ?? '';
        $this->atoll_en                = $profile->getTranslation('atoll', 'en', false) ?? '';
        $this->country_en              = $profile->getTranslation('country', 'en', false) ?? '';
        $this->principal_name_en       = $profile->getTranslation('principal_name', 'en', false) ?? '';
        $this->principal_designation_en = $profile->getTranslation('principal_designation', 'en', false) ?? '';
        $this->principal_message_en    = $profile->getTranslation('principal_message', 'en', false) ?? '';
        $this->existing_logo           = $profile->logo_path;
        $this->existing_principal_photo = $profile->principal_photo_path;
    }

    protected function rules(): array
    {
        return [
            'school_name_en'           => ['nullable', 'string', 'max:255'],
            'motto_en'                 => ['nullable', 'string', 'max:255'],
            'short_description_en'     => ['nullable', 'string'],
            'email'                    => ['nullable', 'email', 'max:255'],
            'phone'                    => ['nullable', 'string', 'max:50'],
            'address_en'               => ['nullable', 'string', 'max:500'],
            'school_island_en'         => ['nullable', 'string', 'max:100'],
            'atoll_en'                 => ['nullable', 'string', 'max:100'],
            'country_en'               => ['nullable', 'string', 'max:100'],
            'principal_name_en'        => ['nullable', 'string', 'max:255'],
            'principal_designation_en' => ['nullable', 'string', 'max:255'],
            'principal_message_en'     => ['nullable', 'string'],
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
            'school_name'           => ['en' => $this->school_name_en],
            'motto'                 => ['en' => $this->motto_en],
            'short_description'     => ['en' => $this->short_description_en],
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'address'               => ['en' => $this->address_en],
            'island'                => ['en' => $this->school_island_en],
            'atoll'                 => ['en' => $this->atoll_en],
            'country'               => ['en' => $this->country_en],
            'principal_name'        => ['en' => $this->principal_name_en],
            'principal_designation' => ['en' => $this->principal_designation_en],
            'principal_message'     => ['en' => $this->principal_message_en],
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
        SchoolProfile::clearSingletonCache();
        Cache::store('file')->forget('footer_data');
        Cache::store('file')->forget('home_static_data');

        $this->dispatch('toast', message: 'School profile saved.');
    }

    public function render()
    {
        return view('livewire.cms.school-profile.school-profile-edit')
            ->layout('layouts.app', ['title' => 'School Profile']);
    }
}
