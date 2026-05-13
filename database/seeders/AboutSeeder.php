<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\FoundingMember;
use App\Models\HistorySection;
use App\Models\LeadershipMember;
use App\Models\Mission;
use Illuminate\Database\Seeder;
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

        $this->seedMission($data['mission'] ?? []);
        $this->seedLeadership($data['leadership'] ?? []);
        $this->seedFoundingMembers($data['foundingMembers'] ?? []);
        $this->seedHistory($data['schoolHistory']['sections'] ?? []);
        $this->seedAchievements($data['achievements'] ?? []);
    }

    private function seedMission(array $data): void
    {
        $mission = Mission::singleton();
        $mission->update([
            'mission' => $data['missionStatement'] ?? null,
            'vision'  => $data['visionStatement'] ?? null,
        ]);
    }

    private function seedLeadership(array $items): void
    {
        foreach ($items as $index => $item) {
            LeadershipMember::updateOrCreate(
                ['name' => $item['name']],
                [
                    'role'       => $item['role'] ?? null,
                    'bio'        => $item['bio'] ?? null,
                    'photo_path' => $item['photoUrl'] ?? null,
                    'is_active'  => true,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function seedFoundingMembers(array $items): void
    {
        foreach ($items as $index => $item) {
            FoundingMember::updateOrCreate(
                ['name' => $item['name']],
                [
                    'subject'    => $item['subject'] ?? null,
                    'tribute'    => $item['tribute'] ?? null,
                    'photo_path' => $item['photoUrl'] ?? null,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function seedHistory(array $sections): void
    {
        // Year labels are not in the JSON — extracted from section titles / context
        $yearLabels = ['1993 – 1995', '1994 – 1995', 'May 1995', 'Present'];

        foreach ($sections as $index => $section) {
            $body = $this->blocksToText($section['blocks'] ?? []);

            HistorySection::updateOrCreate(
                ['title' => $section['title']],
                [
                    'year_label' => $yearLabels[$index] ?? null,
                    'body'       => $body,
                    'sort_order' => $index,
                ]
            );
        }
    }

    private function blocksToText(array $blocks): string
    {
        $parts = [];

        foreach ($blocks as $block) {
            switch ($block['type']) {
                case 'paragraph':
                    $parts[] = $block['text'];
                    break;

                case 'founderList':
                    $lines = array_map(
                        fn ($f) => $f['name'] . ' — ' . $f['subject'],
                        $block['items'] ?? []
                    );
                    $parts[] = implode("\n", $lines);
                    break;

                case 'highlightList':
                    $lines = array_map(
                        fn ($h) => $h['label'] . ': ' . $h['text'],
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
            Achievement::updateOrCreate(
                ['title' => $item['title'], 'year' => $item['year']],
                [
                    'category'    => $item['category'],
                    'description' => $item['description'] ?? null,
                    'award'       => $item['award'] ?? null,
                    'event_name'  => $item['event'] ?? null,
                    'person_name' => $item['personName'] ?? null,
                    'photo_path'  => $item['photoUrl'] ?? null,
                    'is_active'   => true,
                    'sort_order'  => $index,
                ]
            );
        }
    }
}
