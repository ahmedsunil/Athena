<?php

namespace App\Console\Commands;

use App\Services\MoeAcademicCalendarService;
use Exception;
use Illuminate\Console\Command;

class ImportMoeAcademicCalendar extends Command
{
    protected $signature = 'moe:academic-calendar
                            {--year=* : Specific year(s) to import. Leave empty to import every available year.}
                            {--source=' . MoeAcademicCalendarService::DEFAULT_SOURCE_URL . ' : MOE academic calendar URL}
                            {--output=storage/app/moe_academic_calendar.json : Output JSON file path}
                            {--input= : Read JSON from a local file instead of scraping MOE}
                            {--timeout=45 : HTTP request timeout in seconds}';

    protected $description = 'Scrape the MOE academic calendar, export JSON, and sync it into the digital services calendar tables.';

    public function handle(MoeAcademicCalendarService $service): int
    {
        try {
            $payload = $this->option('input')
                ? $service->loadPayloadFromFile((string) $this->option('input'), (string) $this->option('source'))
                : $service->scrape(
                    $this->requestedYears(),
                    (string) $this->option('source'),
                    timeout: (int) $this->option('timeout')
                );

            $payload = $service->filterPayloadByYears($payload, $this->requestedYears());

            $outputPath = $this->resolveOutputPath((string) $this->option('output'));
            $service->writePayload($outputPath, $payload);
            $summary = $service->import($payload);

            $this->renderSummary($payload, $summary, $outputPath);

            return self::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }

    private function requestedYears(): array
    {
        return collect($this->option('year') ?: [])
            ->filter(fn ($year) => (string) $year !== '')
            ->map(fn ($year) => (int) $year)
            ->unique()
            ->values()
            ->all();
    }

    private function resolveOutputPath(string $path): string
    {
        return str_starts_with($path, '/')
            ? $path
            : base_path($path);
    }

    private function renderSummary(array $payload, array $summary, string $outputPath): void
    {
        $this->newLine();
        $this->info('MOE academic calendar import completed.');
        $this->line('Source: ' . data_get($payload, 'source_url', $this->option('source')));
        $this->line('Output: ' . $outputPath);
        $this->line('Years imported: ' . implode(', ', collect($payload['years'] ?? [])->pluck('year')->map(fn ($year) => (string) $year)->all()));
        $this->line('Calendars saved: ' . ($summary['calendars'] ?? 0));
        $this->line('Entries saved: ' . ($summary['entries'] ?? 0));
        $this->line('Active year: ' . ($summary['active_year'] ?? '—'));
    }
}
