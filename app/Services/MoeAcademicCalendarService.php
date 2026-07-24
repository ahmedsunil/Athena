<?php

namespace App\Services;

use App\Models\DigitalServiceCalendar;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MoeAcademicCalendarService
{
    public const DEFAULT_SOURCE_URL = 'https://moe.gov.mv/en/academic-calendar';

    public function availableYears(string $sourceUrl = self::DEFAULT_SOURCE_URL, int $timeout = 45): array
    {
        $response = Http::timeout($timeout)
            ->withHeaders(['Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'])
            ->get($sourceUrl);

        if (! $response->successful()) {
            throw new Exception("Failed to fetch MOE calendar page: HTTP {$response->status()}");
        }

        $years = $this->extractAvailableYears($response->body());

        if ($years === []) {
            throw new Exception('No academic calendar years were found on the MOE page.');
        }

        return $years;
    }

    public function scrape(array $years = [], string $sourceUrl = self::DEFAULT_SOURCE_URL, int $timeout = 45): array
    {
        $cookieJar = new CookieJar;

        $client = Http::timeout($timeout)
            ->withHeaders(['Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'])
            ->withOptions(['cookies' => $cookieJar]);

        $response = $client->get($sourceUrl);

        if (! $response->successful()) {
            throw new Exception("Failed to fetch MOE calendar page: HTTP {$response->status()}");
        }

        $html = $response->body();
        $csrfToken = $this->extractCsrfToken($html, $cookieJar);
        $initialSnapshot = $this->extractCalendarSnapshot($html);

        if (! $initialSnapshot) {
            throw new Exception('Could not find Livewire calendar component on the MOE page.');
        }

        $availableYears = $this->extractAvailableYears($html);

        if (empty($availableYears)) {
            throw new Exception('No academic calendar years were found on the MOE page.');
        }

        if (! empty($years)) {
            $missing = array_diff($years, $availableYears);
            if (! empty($missing)) {
                throw new Exception('Requested year(s) not available: ' . implode(', ', $missing) . '. Available years: ' . implode(', ', $availableYears));
            }
            $yearsToScrape = $years;
        } else {
            $yearsToScrape = $availableYears;
        }

        $baseUrl = parse_url($sourceUrl, PHP_URL_SCHEME) . '://' . parse_url($sourceUrl, PHP_URL_HOST);
        $livewireUrl = $baseUrl . '/livewire/update';

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => $sourceUrl,
        ];

        if ($csrfToken) {
            $headers['X-XSRF-TOKEN'] = $csrfToken;
        }

        $livewireClient = Http::timeout($timeout)
            ->withHeaders($headers)
            ->withOptions(['cookies' => $cookieJar]);

        $parsedYears = [];

        foreach ($yearsToScrape as $year) {
            $snapshot = $this->fetchSnapshotForYear($livewireClient, $livewireUrl, $initialSnapshot, $year);
            $parsed = $this->parseSnapshot($snapshot, $year);
            $parsedYears[] = $parsed;
        }

        return $this->normalizePayload([
            'source_url' => $sourceUrl,
            'scraped_at' => now()->toISOString(),
            'available_years' => $availableYears,
            'years' => $parsedYears,
        ], $sourceUrl);
    }

    private function extractCsrfToken(string $html, CookieJar $cookieJar): ?string
    {
        // Prefer XSRF-TOKEN cookie (URL-encoded, Laravel standard)
        foreach ($cookieJar as $cookie) {
            if ($cookie->getName() === 'XSRF-TOKEN') {
                return urldecode($cookie->getValue());
            }
        }

        // Fallback: meta csrf-token tag
        if (preg_match('/<meta[^>]+name=["\']csrf-token["\'][^>]+content=["\']([^"\']+)["\']/', $html, $m)) {
            return $m[1];
        }

        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+name=["\']csrf-token["\']/', $html, $m)) {
            return $m[1];
        }

        return null;
    }

    private function extractCalendarSnapshot(string $html): ?array
    {
        preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

        foreach ($matches[1] as $raw) {
            $snapshot = json_decode(html_entity_decode($raw, ENT_QUOTES | ENT_HTML5, 'UTF-8'), true);

            if (is_array($snapshot) && isset($snapshot['data'])) {
                $dataStr = json_encode($snapshot['data']);
                if (str_contains($dataStr, 'compiled_events') || str_contains($dataStr, 'filtered_year')) {
                    return $snapshot;
                }
            }
        }

        return null;
    }

    private function extractAvailableYears(string $html): array
    {
        if (! preg_match_all('/<option[^>]*(?:value=["\'](\d{4})["\'])[^>]*>/i', $html, $matches)) {
            preg_match_all('/<option[^>]*>(\d{4})<\/option>/i', $html, $matches);
        }

        $years = array_unique(array_map('intval', $matches[1] ?? []));
        sort($years);

        return array_values(array_filter($years));
    }

    private function fetchSnapshotForYear(PendingRequest $client, string $livewireUrl, array $snapshot, int $year): array
    {
        $response = $client->post($livewireUrl, [
            'components' => [
                [
                    'snapshot' => json_encode($snapshot),
                    'updates' => ['filtered_year' => (string) $year],
                    'calls' => [],
                ],
            ],
        ]);

        if (! $response->successful()) {
            throw new Exception("Livewire update failed for year {$year}: HTTP {$response->status()}");
        }

        $data = $response->json();
        $componentData = data_get($data, 'components.0');

        if (! $componentData) {
            throw new Exception("No component data returned for year {$year}.");
        }

        $rawSnapshot = data_get($componentData, 'snapshot');
        $updatedSnapshot = is_string($rawSnapshot) ? json_decode($rawSnapshot, true) : $rawSnapshot;

        if (! is_array($updatedSnapshot)) {
            throw new Exception("Invalid snapshot returned for year {$year}.");
        }

        return $updatedSnapshot;
    }

    private function unwrapLivewire(mixed $value): mixed
    {
        if (is_array($value)) {
            if (count($value) === 2 && is_array($value[1]) && array_key_exists('s', $value[1])) {
                return $this->unwrapLivewire($value[0]);
            }

            return array_map(fn ($item) => $this->unwrapLivewire($item), $value);
        }

        return $value;
    }

    private function parseSnapshot(array $snapshot, int $year): array
    {
        $data = $this->unwrapLivewire(data_get($snapshot, 'data', []));
        $rawYear = data_get($data, 'filtered_year');
        $parsedYear = is_numeric($rawYear) ? (int) $rawYear : $year;

        $compiledEvents = data_get($data, 'compiled_events', []);
        if (is_array($compiledEvents) && array_is_list($compiledEvents)) {
            $compiledEvents = $compiledEvents[0] ?? [];
        }

        $events = [];
        foreach ((array) $compiledEvents as $eventId => $eventPayload) {
            $event = $this->unwrapLivewire($eventPayload ?? []);

            $setting = $this->unwrapLivewire(data_get($event, 'calendar_widget_setting', []));
            if (is_array($setting) && array_is_list($setting)) {
                $setting = $setting[0] ?? [];
            }

            $startDate = $this->toIsoDate(
                data_get($event, 'start_date') ?: data_get($event, 'start_date_long') ?: data_get($event, 'date_start')
            );
            $endDate = $this->toIsoDate(
                data_get($event, 'end_date') ?: data_get($event, 'end_date_long') ?: data_get($event, 'date_end')
            );
            $title = trim((string) (data_get($event, 'event_title_en') ?: data_get($event, 'title_en') ?: data_get($event, 'title') ?: data_get($event, 'name') ?: ''));
            $eventType = trim((string) (data_get($event, 'event_type') ?: data_get($setting, 'event_type_en') ?: data_get($event, 'type') ?: ''));

            $events[] = [
                'id' => (string) $eventId,
                'calendar_widget_setting_id' => (int) data_get($event, 'calendar_widget_setting_id', data_get($setting, 'id', 0)) ?: null,
                'year' => $parsedYear ?: ($startDate ? (int) substr($startDate, 0, 4) : $year),
                'title_en' => $title,
                'event_type' => $eventType,
                'bg_color' => trim((string) (data_get($setting, 'bg_color') ?: '')),
                'text_color' => trim((string) (data_get($setting, 'text_color') ?: '')),
                'border_color' => trim((string) (data_get($setting, 'border_color') ?: '')),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'start_month' => trim((string) (data_get($event, 'start_month') ?: '')),
                'start_day' => trim((string) (data_get($event, 'start_day') ?: '')),
                'description_en' => trim((string) (data_get($event, 'description_en') ?: data_get($event, 'short_description') ?: data_get($event, 'description') ?: data_get($event, 'fullDescription') ?: '')),
                'is_tentative' => $this->normalizeTentative($event),
            ];
        }

        usort($events, function (array $a, array $b) {
            $left = $a['start_date'] ?? '9999-99-99';
            $right = $b['start_date'] ?? '9999-99-99';

            return $left !== $right ? strcmp($left, $right) : strcmp($a['title_en'] ?? '', $b['title_en'] ?? '');
        });

        $eventTypes = $this->parseFilterEventTypes(data_get($data, 'filterEventTypes', []));

        return [
            'year' => $parsedYear ?: $year,
            'event_count' => count($events),
            'event_types' => $eventTypes,
            'events' => $events,
        ];
    }

    private function parseFilterEventTypes(mixed $filterEventTypes): array
    {
        $unwrapped = $this->unwrapLivewire($filterEventTypes);

        if (! is_array($unwrapped)) {
            return [];
        }

        $types = [];
        foreach ($unwrapped as $item) {
            if (! is_array($item) || ! isset($item['id'])) {
                continue;
            }
            $types[] = [
                'id' => (int) $item['id'],
                'label' => trim((string) ($item['label'] ?? '')),
                'color' => trim((string) ($item['color'] ?? '')),
                'text_color' => trim((string) ($item['text_color'] ?? '')),
            ];
        }

        usort($types, fn ($a, $b) => $a['id'] <=> $b['id']);

        return $types;
    }

    private function normalizeTentative(array $event): bool
    {
        $source = strtolower(implode(' ', array_map(
            fn ($v) => trim((string) ($v ?? '')),
            [
                data_get($event, 'is_tentative'),
                data_get($event, 'tentative'),
                data_get($event, 'title_en'),
                data_get($event, 'description_en'),
                data_get($event, 'fullDescription'),
            ]
        )));

        return str_contains($source, 'tentative') || str_contains($source, 'subject to change');
    }

    private function toIsoDate(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        try {
            return Carbon::parse((string) $value)->toDateString();
        } catch (Exception) {
            return null;
        }
    }

    public function loadPayloadFromFile(string $path, string $fallbackSourceUrl = self::DEFAULT_SOURCE_URL): array
    {
        $resolvedPath = $this->resolvePath($path);

        if (! File::exists($resolvedPath)) {
            throw new Exception("Input file not found: {$resolvedPath}");
        }

        $decoded = json_decode(File::get($resolvedPath), true);

        if (! is_array($decoded)) {
            throw new Exception("Input file is not valid JSON: {$resolvedPath}");
        }

        return $this->normalizePayload($decoded, $fallbackSourceUrl);
    }

    public function writePayload(string $path, array $payload): void
    {
        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }

    public function import(array $payload, ?int $targetCalendarId = null): array
    {
        $years = collect($payload['years'] ?? [])
            ->filter(fn (array $yearData) => (int) data_get($yearData, 'year') > 0)
            ->sortBy('year')
            ->values();

        if ($years->isEmpty()) {
            throw new Exception('No academic calendar years found to import.');
        }

        if ($targetCalendarId) {
            return $this->importIntoCalendar($payload, $years->first(), $targetCalendarId);
        }

        $activeYear = (int) $years->last()['year'];
        $calendarCount = 0;
        $entryCount = 0;

        DigitalServiceCalendar::query()->update(['is_active' => false]);

        foreach ($years as $index => $yearData) {
            $year = (int) $yearData['year'];
            $calendar = DigitalServiceCalendar::updateOrCreate(
                ['year' => $year],
                [
                    'title' => $this->calendarTitle($yearData, $year),
                    'description' => $this->calendarDescription($payload, $yearData),
                    'sort_order' => $index,
                    'is_active' => $year === $activeYear,
                ]
            );

            $entryCount += $this->replaceEntries($calendar, $yearData);
            $calendarCount++;
        }

        return [
            'calendars' => $calendarCount,
            'entries' => $entryCount,
            'active_year' => $activeYear,
        ];
    }

    public function importIntoCalendar(array $payload, array $yearData, int $calendarId): array
    {
        $calendar = DigitalServiceCalendar::findOrFail($calendarId);
        $year = (int) data_get($yearData, 'year');

        $calendar->update([
            'title' => $this->calendarTitle($yearData, $year),
            'year' => $year,
            'description' => $this->calendarDescription($payload, $yearData),
            'sort_order' => $calendar->sort_order,
            'is_active' => true,
        ]);

        DigitalServiceCalendar::where('id', '!=', $calendar->id)->update(['is_active' => false]);

        return [
            'calendars' => 1,
            'entries' => $this->replaceEntries($calendar, $yearData),
            'active_year' => $year,
        ];
    }

    public function filterPayloadByYears(array $payload, array $years): array
    {
        if ($years === []) {
            return $payload;
        }

        $filteredYears = collect($payload['years'] ?? [])
            ->filter(fn (array $yearData) => in_array((int) data_get($yearData, 'year'), $years, true))
            ->values()
            ->all();

        if ($filteredYears === []) {
            throw new Exception('None of the requested year(s) exist in the payload.');
        }

        $payload['years'] = $filteredYears;
        $payload['available_years'] = collect($payload['available_years'] ?? [])
            ->filter(fn ($year) => in_array((int) $year, $years, true))
            ->values()
            ->all();

        return $payload;
    }

    private function replaceEntries(DigitalServiceCalendar $calendar, array $yearData): int
    {
        $calendar->entries()->delete();

        $count = 0;
        foreach (collect(data_get($yearData, 'events', []))->values() as $index => $event) {
            $title = $this->normalizeCalendarEntryTitle($event);
            $date = $this->normalizeDate(data_get($event, 'start_date') ?: data_get($event, 'date'));

            if ($title === '' || ! $date) {
                continue;
            }

            $calendar->entries()->create([
                'sort_order' => $index,
                'title' => $title,
                'date' => $date,
                'end_date' => $this->normalizeDate(data_get($event, 'end_date')),
                'type' => $this->normalizeCalendarEntryType((string) data_get($event, 'event_type', data_get($event, 'type', 'event'))),
                'description' => $this->normalizeCalendarEntryDescription($event),
                'is_active' => true,
                'is_tentative' => (bool) data_get($event, 'is_tentative', false),
            ]);

            $count++;
        }

        return $count;
    }

    private function normalizePayload(array $payload, string $fallbackSourceUrl): array
    {
        $years = collect($payload['years'] ?? [])
            ->map(function ($yearData) {
                $year = (int) data_get($yearData, 'year', 0);
                $events = collect(data_get($yearData, 'events', []))
                    ->map(function ($event) use ($year) {
                        $startDate = $this->normalizeDate(data_get($event, 'start_date') ?: data_get($event, 'date'));
                        $endDate = $this->normalizeDate(data_get($event, 'end_date'));

                        return [
                            'id' => (string) data_get($event, 'id', ''),
                            'calendar_widget_setting_id' => data_get($event, 'calendar_widget_setting_id') ? (int) data_get($event, 'calendar_widget_setting_id') : null,
                            'year' => (int) (data_get($event, 'year') ?: $year),
                            'title_en' => trim((string) data_get($event, 'title_en', data_get($event, 'title', ''))),
                            'event_type' => trim((string) data_get($event, 'event_type', data_get($event, 'type', 'event'))),
                            'bg_color' => trim((string) data_get($event, 'bg_color', '')),
                            'text_color' => trim((string) data_get($event, 'text_color', '')),
                            'border_color' => trim((string) data_get($event, 'border_color', '')),
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'start_month' => trim((string) data_get($event, 'start_month', '')),
                            'start_day' => trim((string) data_get($event, 'start_day', '')),
                            'description_en' => trim((string) data_get($event, 'description_en', data_get($event, 'description', ''))),
                            'is_tentative' => (bool) data_get($event, 'is_tentative', false),
                        ];
                    })
                    ->filter(fn (array $event) => filled($event['start_date']))
                    ->values()
                    ->all();

                return [
                    'year' => $year,
                    'event_count' => count($events),
                    'event_types' => data_get($yearData, 'event_types', []),
                    'events' => $events,
                ];
            })
            ->filter(fn (array $yearData) => $yearData['year'] > 0)
            ->sortBy('year')
            ->values()
            ->all();

        return [
            'source_url' => data_get($payload, 'source_url', $fallbackSourceUrl),
            'scraped_at' => data_get($payload, 'scraped_at', now()->toISOString()),
            'available_years' => collect(data_get($payload, 'available_years', []))
                ->filter()
                ->map(fn ($year) => (int) $year)
                ->unique()
                ->sort()
                ->values()
                ->all(),
            'years' => $years,
        ];
    }

    private function calendarTitle(array $yearData, int $year): string
    {
        $title = trim((string) data_get($yearData, 'title_en', ''));

        return $title !== '' ? $title : "Academic Calendar {$year}";
    }

    private function calendarDescription(array $payload, array $yearData): ?string
    {
        $sourceUrl = (string) data_get($payload, 'source_url', self::DEFAULT_SOURCE_URL);
        $scrapedAt = (string) data_get($payload, 'scraped_at', now()->toISOString());
        $summary = trim((string) data_get($yearData, 'summary', ''));

        $parts = array_filter([
            $summary,
            'Imported from MOE academic calendar.',
            "Source: {$sourceUrl}",
            "Scraped at: {$scrapedAt}",
        ]);

        return $parts ? implode(' ', $parts) : null;
    }

    private function normalizeCalendarEntryTitle(array $event): string
    {
        foreach (['title_en', 'title', 'name'] as $key) {
            $value = trim((string) data_get($event, $key, ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    private function normalizeCalendarEntryDescription(array $event): ?string
    {
        $description = trim((string) data_get($event, 'description_en', data_get($event, 'description', '')));

        return $description !== '' ? $description : null;
    }

    private function normalizeCalendarEntryType(string $type): string
    {
        $value = Str::of($type)->lower();

        if ($value->contains('holiday')) {
            return 'holiday';
        }

        if ($value->contains('exam') || $value->contains('examination')) {
            return 'exam';
        }

        if ($value->contains('academic') || $value->contains('term')) {
            return 'term';
        }

        return 'event';
    }

    private function normalizeDate(mixed $value): ?string
    {
        if (! filled($value)) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (Exception) {
            return null;
        }
    }

    private function resolvePath(string $path): string
    {
        return Str::startsWith($path, ['/']) ? $path : base_path($path);
    }
}
