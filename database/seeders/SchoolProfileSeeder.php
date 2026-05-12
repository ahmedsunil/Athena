<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SchoolProfileSeeder extends Seeder
{
    public function run(): void
    {
        $homeJson = $this->readJson(public_path('api/home.json'));
        $aboutJson = $this->readJson(public_path('api/about.json'));

        if ($homeJson === [] && $aboutJson === []) {
            return;
        }

        $school = $aboutJson['school'] ?? [];
        $principal = $homeJson['principal'] ?? ($aboutJson['principal'] ?? []);
        $contact = $homeJson['contact'] ?? [];
        $addressParts = collect(explode(',', $contact['address'] ?? ''))->map(fn ($part) => trim($part))->filter()->values();

        SchoolProfile::updateOrCreate(
            ['id' => 1],
            [
                'school_name' => $school['name'] ?? $this->schoolNameFromPrincipalTitle($principal['title'] ?? null),
                'motto' => $school['motto'] ?? null,
                'short_description' => $school['description'] ?? null,
                'logo_path' => null,
                'email' => $contact['email'] ?? null,
                'phone' => $contact['phone'] ?? null,
                'address' => $contact['address'] ?? null,
                'island' => $addressParts->get(1),
                'atoll' => $addressParts->get(2),
                'country' => $addressParts->last(),
                'principal_name' => $principal['name'] ?? null,
                'principal_designation' => Str::before($principal['title'] ?? 'Principal', ','),
                'principal_message' => $principal['message'] ?? null,
                'principal_photo_path' => $principal['photoUrl'] ?? null,
            ]
        );
    }

    private function readJson(string $path): array
    {
        if (! File::exists($path)) {
            return [];
        }

        return json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);
    }

    private function schoolNameFromPrincipalTitle(?string $title): ?string
    {
        if (! $title || ! str_contains($title, ',')) {
            return null;
        }

        return trim(Str::after($title, ','));
    }
}
