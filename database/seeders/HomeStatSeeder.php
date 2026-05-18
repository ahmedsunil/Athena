<?php

namespace Database\Seeders;

use App\Models\HomeStat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeStatSeeder extends Seeder
{
    public function run(): void
    {
        $homePath = public_path('api/home.json');

        if (! File::exists($homePath)) {
            return;
        }

        $homeJson = json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR);
        $stats = $homeJson['stats'] ?? [];

        DB::transaction(function () use ($stats) {
            HomeStat::query()->delete();

            foreach ($stats as $index => $stat) {
                HomeStat::create([
                    'title'      => $this->translation($stat, 'label'),
                    'value'      => $stat['value'],
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
