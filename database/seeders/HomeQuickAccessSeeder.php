<?php

namespace Database\Seeders;

use App\Models\HomeQuickAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeQuickAccessSeeder extends Seeder
{
    public function run(): void
    {
        $homePath = public_path('api/home.json');

        if (! File::exists($homePath)) {
            return;
        }

        $homeJson = json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR);
        $quickLinks = $homeJson['quickLinks'] ?? [];

        DB::transaction(function () use ($quickLinks) {
            HomeQuickAccess::query()->delete();

            foreach ($quickLinks as $index => $quickLink) {
                HomeQuickAccess::create([
                    'title'      => $this->translation($quickLink, 'label'),
                    'icon_key'   => $quickLink['icon'],
                    'link_key'   => $quickLink['href'],
                    'is_active'  => true,
                    'sort_order' => $index,
                ]);
            }
        });
    }

    private function translation(array $item, string $key): array
    {
        return [
            'en' => $item[$key] ?? '',
            'dv' => $item["{$key}_dv"] ?? ($item[$key] ?? ''),
        ];
    }
}
