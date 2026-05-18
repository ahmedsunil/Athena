<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\FoundingMember;
use App\Models\HistorySection;
use App\Models\LeadershipMember;
use App\Models\Mission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        $path = public_path('api/about.json');

        if (! File::exists($path)) {
            return;
        }

        $data = json_decode(File::get($path), true, flags: JSON_THROW_ON_ERROR);

        DB::transaction(function () use ($data) {
            LeadershipMember::query()->delete();
            FoundingMember::query()->delete();
            HistorySection::query()->delete();
            Achievement::query()->delete();
            Mission::whereKeyNot(1)->delete();

            $this->seedMission($data['mission'] ?? []);
            $this->seedLeadership($data['leadership'] ?? []);
            $this->seedFoundingMembers($data['foundingMembers'] ?? []);
            $this->seedHistory($data['schoolHistory']['sections'] ?? []);
            $this->seedAchievements($data['achievements'] ?? []);
        });
    }

    private function seedMission(array $data): void
    {
        $mission = Mission::singleton();
        $mission->update([
            'mission' => $this->translation($data, 'missionStatement'),
            'vision'  => $this->translation($data, 'visionStatement'),
        ]);
    }

    private function seedLeadership(array $items): void
    {
        foreach ($items as $index => $item) {
            LeadershipMember::create([
                'name'       => $item['name'],
                'name_dv'    => $item['name_dv'] ?? null,
                'role'       => $this->translation($item, 'role'),
                'bio'        => $this->translation($item, 'bio'),
                'photo_path' => $item['photoUrl'] ?? null,
                'is_active'  => true,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedFoundingMembers(array $items): void
    {
        foreach ($items as $index => $item) {
            FoundingMember::create([
                'name'       => $item['name'],
                'name_dv'    => $item['name_dv'] ?? null,
                'subject'    => $this->translation($item, 'subject'),
                'tribute'    => $this->translation($item, 'tribute'),
                'photo_path' => $item['photoUrl'] ?? null,
                'sort_order' => $index,
            ]);
        }
    }

    private function seedHistory(array $sections): void
    {
        // Year labels are extracted from section titles and context.
        $yearLabels = ['1993 – 1995', '1994 – 1995', 'May 1995', 'Present'];

        foreach ($sections as $index => $section) {
            HistorySection::create([
                'title'      => $this->translation($section, 'title'),
                'year_label' => $yearLabels[$index] ?? null,
                'body'       => [
                    'en' => $this->blocksToText($section['blocks'] ?? [], 'en'),
                    'dv' => $this->blocksToText($section['blocks'] ?? [], 'dv'),
                ],
                'sort_order' => $index,
            ]);
        }
    }

    private function blocksToText(array $blocks, string $locale): string
    {
        $parts = [];
        $suffix = $locale === 'dv' ? '_dv' : '';

        foreach ($blocks as $block) {
            switch ($block['type']) {
                case 'paragraph':
                    $parts[] = $block["text{$suffix}"] ?? $block['text'];
                    break;

                case 'founderList':
                    $lines = array_map(
                        fn ($f) => ($f["name{$suffix}"] ?? $f['name']) . ' - ' . ($f["subject{$suffix}"] ?? $f['subject']),
                        $block['items'] ?? []
                    );
                    $parts[] = implode("\n", $lines);
                    break;

                case 'highlightList':
                    $lines = array_map(
                        fn ($h) => ($h["label{$suffix}"] ?? $h['label']) . ': ' . ($h["text{$suffix}"] ?? $h['text']),
                        $block['items'] ?? []
                    );
                    $parts[] = implode("\n", $lines);
                    break;
            }
        }

        return implode("\n\n", $parts);
    }

    private function seedAchievements(array $items): void
    {
        foreach ($items as $index => $item) {
            Achievement::create([
                'title'          => $this->translation($item, 'title'),
                'category'       => $item['category'],
                'year'           => $item['year'],
                'description'    => $this->translation($item, 'description'),
                'award'          => $this->translation($item, 'award'),
                'event_name'     => $this->translation($item, 'event'),
                'person_name'    => $item['personName'] ?? null,
                'person_name_dv' => $item['personName_dv'] ?? null,
                'photo_path'     => $item['photoUrl'] ?? null,
                'is_active'      => true,
                'sort_order'     => $index,
            ]);
        }
    }

    private function translation(array $item, string $key): array
    {
        return [
            'en' => $item[$key] ?? '',
            'dv' => $item["{$key}_dv"] ?? ($item[$key] ?? ''),
        ];
    }
}
