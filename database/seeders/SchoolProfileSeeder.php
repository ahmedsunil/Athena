<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($school, $principal, $contact, $addressParts) {
            SchoolProfile::whereKeyNot(1)->delete();

            SchoolProfile::updateOrCreate(
                ['id' => 1],
                [
                    'school_name' => $this->translation($school, 'name', $this->schoolNameFromPrincipalTitle($principal['title'] ?? null)),
                    'motto' => $this->translation($school, 'motto'),
                    'short_description' => $this->translation($school, 'description'),
                    'logo_path' => null,
                    'email' => $contact['email'] ?? null,
                    'phone' => $contact['phone'] ?? null,
                    'address' => $this->translation($contact, 'address'),
                    'island' => ['en' => $addressParts->get(1)],
                    'atoll' => ['en' => $addressParts->get(2)],
                    'country' => ['en' => $addressParts->last()],
                    'principal_name' => $this->translation($principal, 'name'),
                    'principal_designation' => ['en' => Str::before($principal['title'] ?? 'Principal', ',')],
                    'principal_message' => $this->translation($principal, 'message'),
                    'principal_photo_path' => $principal['photoUrl'] ?? null,
                ]
            );
        });
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

    private function translation(array $item, string $key, ?string $fallback = null): array
    {
        $english = $item[$key] ?? $fallback;

        return [
            'en' => $english,
        ];
    }
}
