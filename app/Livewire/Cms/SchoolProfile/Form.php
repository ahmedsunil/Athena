<?php

namespace App\Livewire\Cms\SchoolProfile;

use App\Models\SchoolProfile;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public ?int $profileId = null;

    public string $name = '';
    public $founded_year = null;
    public ?string $motto = null;
    public ?string $tagline = null;
    public ?string $description = null;
    public ?string $logo_path = null;
    public ?string $mission_statement = null;
    public ?string $vision_statement = null;
    public string $contact_address = '';
    public string $contact_phone = '';
    public string $contact_email = '';

    public $logo = null;

    public function mount(): void
    {
        $profile = SchoolProfile::first();

        if (! $profile) {
            return;
        }

        $this->profileId = $profile->id;
        $this->name = $profile->name;
        $this->founded_year = $profile->founded_year;
        $this->motto = $profile->motto;
        $this->tagline = $profile->tagline;
        $this->description = $profile->description;
        $this->logo_path = $profile->logo_path;
        $this->mission_statement = $profile->mission_statement;
        $this->vision_statement = $profile->vision_statement;
        $this->contact_address = $profile->contact_address;
        $this->contact_phone = $profile->contact_phone;
        $this->contact_email = $profile->contact_email;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'founded_year' => ['required', 'integer', 'digits:4', 'min:1800', 'max:' . now()->year],
            'motto' => ['nullable', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'logo' => [Rule::requiredIf(! $this->logo_path), 'nullable', 'image', 'max:2048'],
            'mission_statement' => ['nullable', 'string'],
            'vision_statement' => ['nullable', 'string'],
            'contact_address' => ['required', 'string'],
            'contact_phone' => ['required', 'string'],
            'contact_email' => ['required', 'email', 'max:255'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $validated['name'],
            'founded_year' => $validated['founded_year'],
            'motto' => $validated['motto'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'mission_statement' => $validated['mission_statement'],
            'vision_statement' => $validated['vision_statement'],
            'contact_address' => $validated['contact_address'],
            'contact_phone' => $validated['contact_phone'],
            'contact_email' => $validated['contact_email'],
            'logo_path' => $this->logo_path,
        ];

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('school', 'public');
        }

        $profile = SchoolProfile::updateOrCreate(
            ['id' => $this->profileId ?? 1],
            $data
        );

        $this->profileId = $profile->id;
        $this->logo_path = $profile->logo_path;
        $this->logo = null;

        $this->dispatch('toast', message: 'School profile saved.');
    }

    public function render()
    {
        return view('livewire.cms.school-profile.form');
    }
}
