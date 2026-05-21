<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceCalendarEntry;
use App\Models\Event;
use App\Services\MoeAcademicCalendarService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

class CalendarEntriesIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $calendarId = null;

    public string $calendarTitle = '';
    public int $calendarYear = 2026;
    public string $calendarDescription = '';
    public int $calendarSortOrder = 0;
    public bool $calendarIsActive = false;
    public ?int $calendarEditingId = null;
    public bool $showCalendarForm = false;

    public string $statTeachingDays = '';
    public string $statExamDays = '';
    public string $statReportPrepDays = '';
    public string $statTeacherPdDays = '';
    public string $statNonTeachingDays = '';
    public string $statTotalDays = '';

    public string $term1Dates = '';
    public string $term1TotalDays = '';
    public string $term1TeachingDays = '';
    public string $term1ExamDays = '';
    public string $term2Dates = '';
    public string $term2TotalDays = '';
    public string $term2TeachingDays = '';
    public string $term2ExamDays = '';

    public string $examSeries1Title = '';
    public string $examSeries1Sub = '';
    public string $examSeries1Period = '';
    public string $examSeries2Title = '';
    public string $examSeries2Sub = '';
    public string $examSeries2Period = '';
    public string $examSeries3Title = '';
    public string $examSeries3Sub = '';
    public string $examSeries3Period = '';

    public string $title = '';
    public string $date = '';
    public string $end_date = '';
    public string $type = 'event';
    public string $description = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public bool $is_tentative = false;
    public ?int $editingId = null;
    public bool $showEntryForm = false;
    public bool $showSyncModal = false;

    public array $types = ['event', 'term', 'holiday', 'exam'];

    public function selectCalendar(int $id): void
    {
        $this->calendarId = $id;
        $this->showCalendarForm = false;
        $this->resetCalendarForm();
        $this->resetForm();
        $this->resetPage();
    }

    public function backToCalendars(): void
    {
        $this->calendarId = null;
        $this->showEntryForm = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function newCalendar(): void
    {
        $this->resetCalendarForm();
        $this->showCalendarForm = true;
    }

    public function editCalendar(int $id): void
    {
        $calendar = DigitalServiceCalendar::findOrFail($id);

        $this->calendarEditingId    = $calendar->id;
        $this->calendarTitle        = $calendar->title;
        $this->calendarYear         = $calendar->year ?? (int) date('Y');
        $this->calendarDescription  = $calendar->description ?? '';
        $this->calendarSortOrder    = $calendar->sort_order;
        $this->calendarIsActive     = $calendar->is_active;
        $this->statTeachingDays    = $calendar->stat_teaching_days ?? '';
        $this->statExamDays        = $calendar->stat_exam_days ?? '';
        $this->statReportPrepDays  = $calendar->stat_report_prep_days ?? '';
        $this->statTeacherPdDays   = $calendar->stat_teacher_pd_days ?? '';
        $this->statNonTeachingDays = $calendar->stat_non_teaching_days ?? '';
        $this->statTotalDays       = $calendar->stat_total_days ?? '';

        $this->term1Dates        = $calendar->term1_dates ?? '';
        $this->term1TotalDays    = $calendar->term1_total_days ?? '';
        $this->term1TeachingDays = $calendar->term1_teaching_days ?? '';
        $this->term1ExamDays     = $calendar->term1_exam_days ?? '';
        $this->term2Dates        = $calendar->term2_dates ?? '';
        $this->term2TotalDays    = $calendar->term2_total_days ?? '';
        $this->term2TeachingDays = $calendar->term2_teaching_days ?? '';
        $this->term2ExamDays     = $calendar->term2_exam_days ?? '';

        $series = $calendar->exam_series ?? [];
        $this->examSeries1Title  = $series[0]['title']  ?? '';
        $this->examSeries1Sub    = $series[0]['sub']    ?? '';
        $this->examSeries1Period = $series[0]['period'] ?? '';
        $this->examSeries2Title  = $series[1]['title']  ?? '';
        $this->examSeries2Sub    = $series[1]['sub']    ?? '';
        $this->examSeries2Period = $series[1]['period'] ?? '';
        $this->examSeries3Title  = $series[2]['title']  ?? '';
        $this->examSeries3Sub    = $series[2]['sub']    ?? '';
        $this->examSeries3Period = $series[2]['period'] ?? '';

        $this->showCalendarForm = true;
    }

    public function saveCalendar(): void
    {
        $this->validate($this->calendarRules());

        if ($this->calendarIsActive) {
            DigitalServiceCalendar::where('id', '!=', $this->calendarEditingId ?? 0)->update(['is_active' => false]);
        }

        $data = [
            'title'                  => $this->calendarTitle,
            'year'                   => $this->calendarYear,
            'description'            => $this->calendarDescription ?: null,
            'sort_order'             => $this->calendarSortOrder,
            'is_active'              => $this->calendarIsActive,
            'stat_teaching_days'     => $this->statTeachingDays !== '' ? (float) $this->statTeachingDays : null,
            'stat_exam_days'         => $this->statExamDays !== '' ? (float) $this->statExamDays : null,
            'stat_report_prep_days'  => $this->statReportPrepDays !== '' ? (float) $this->statReportPrepDays : null,
            'stat_teacher_pd_days'   => $this->statTeacherPdDays !== '' ? (float) $this->statTeacherPdDays : null,
            'stat_non_teaching_days' => $this->statNonTeachingDays !== '' ? (float) $this->statNonTeachingDays : null,
            'stat_total_days'        => $this->statTotalDays !== '' ? (float) $this->statTotalDays : null,
            'term1_dates'            => $this->term1Dates ?: null,
            'term1_total_days'       => $this->term1TotalDays !== '' ? (float) $this->term1TotalDays : null,
            'term1_teaching_days'    => $this->term1TeachingDays !== '' ? (float) $this->term1TeachingDays : null,
            'term1_exam_days'        => $this->term1ExamDays !== '' ? (float) $this->term1ExamDays : null,
            'term2_dates'            => $this->term2Dates ?: null,
            'term2_total_days'       => $this->term2TotalDays !== '' ? (float) $this->term2TotalDays : null,
            'term2_teaching_days'    => $this->term2TeachingDays !== '' ? (float) $this->term2TeachingDays : null,
            'term2_exam_days'        => $this->term2ExamDays !== '' ? (float) $this->term2ExamDays : null,
            'exam_series'            => array_values(array_filter([
                $this->examSeries1Title ? ['title' => $this->examSeries1Title, 'sub' => $this->examSeries1Sub, 'period' => $this->examSeries1Period] : null,
                $this->examSeries2Title ? ['title' => $this->examSeries2Title, 'sub' => $this->examSeries2Sub, 'period' => $this->examSeries2Period] : null,
                $this->examSeries3Title ? ['title' => $this->examSeries3Title, 'sub' => $this->examSeries3Sub, 'period' => $this->examSeries3Period] : null,
            ])) ?: null,
        ];

        if ($this->calendarEditingId) {
            DigitalServiceCalendar::findOrFail($this->calendarEditingId)->update($data);
            $this->dispatch('toast', message: 'Calendar updated.');
        } else {
            $calendar = DigitalServiceCalendar::create($data);
            $this->calendarId = $calendar->id;
            $this->dispatch('toast', message: 'Calendar added.');
        }

        $this->resetCalendarForm();
    }

    public function deleteCalendar(int $id): void
    {
        DigitalServiceCalendar::findOrFail($id)->delete();

        if ($this->calendarId === $id) {
            $this->backToCalendars();
        }

        $this->dispatch('toast', message: 'Calendar deleted.');
    }

    public function cancelCalendar(): void
    {
        $this->resetCalendarForm();
    }

    public function openGenerateFromMoe(?int $calendarId = null): void
    {
        $targetCalendarId = $calendarId ?? $this->calendarId;
        $targetCalendar = $targetCalendarId ? DigitalServiceCalendar::find($targetCalendarId) : null;

        if (! $targetCalendar) {
            $this->dispatch('toast', message: 'Select a calendar before generating from MOE.');
            return;
        }

        $year = $targetCalendar->year ?: (int) date('Y');

        try {
            $service = app(MoeAcademicCalendarService::class);
            $payload = $service->scrape([$year], MoeAcademicCalendarService::DEFAULT_SOURCE_URL, 45);
            $service->import($payload, $targetCalendar->id);

            $this->calendarId = $targetCalendar->id;
            $this->dispatch('toast', message: "Generated academic calendar {$year} from MOE.");
            $this->resetPage();
        } catch (Throwable $e) {
            $this->dispatch('toast', message: $e->getMessage());
        }
    }

    public function syncEvents(): void
    {
        if (! $this->calendarId) {
            return;
        }

        $calendar = DigitalServiceCalendar::find($this->calendarId);
        if (! $calendar?->year) {
            $this->dispatch('toast', message: 'Calendar has no year set — cannot sync.');
            return;
        }

        $events = Event::where('is_active', true)
            ->whereYear('date_start', $calendar->year)
            ->get();

        foreach ($events as $event) {
            DigitalServiceCalendarEntry::updateOrCreate(
                [
                    'calendar_id' => $this->calendarId,
                    'title'       => $event->title,
                    'date'        => $event->date_start->format('Y-m-d'),
                ],
                [
                    'end_date'    => $event->date_end?->format('Y-m-d'),
                    'type'        => 'event',
                    'description' => $event->short_description,
                    'is_active'   => true,
                    'sort_order'  => 0,
                ]
            );
        }

        $this->showSyncModal = false;
        $this->dispatch('toast', message: "Synced {$events->count()} event(s) from Events.");
        $this->resetPage();
    }

    public function newEntry(): void
    {
        $this->resetForm();
        $this->showEntryForm = true;
    }

    protected function calendarRules(): array
    {
        return [
            'calendarTitle'        => ['required', 'string', 'max:255'],
            'calendarYear'         => ['required', 'integer', 'min:2000', 'max:2100'],
            'calendarDescription'  => ['nullable', 'string'],
            'calendarSortOrder'    => ['integer', 'min:0'],
            'calendarIsActive'     => ['boolean'],
            'statTeachingDays'    => ['nullable', 'numeric', 'min:0'],
            'statExamDays'        => ['nullable', 'numeric', 'min:0'],
            'statReportPrepDays'  => ['nullable', 'numeric', 'min:0'],
            'statTeacherPdDays'   => ['nullable', 'numeric', 'min:0'],
            'statNonTeachingDays' => ['nullable', 'numeric', 'min:0'],
            'statTotalDays'       => ['nullable', 'numeric', 'min:0'],
            'term1Dates'          => ['nullable', 'string', 'max:100'],
            'term1TotalDays'      => ['nullable', 'numeric', 'min:0'],
            'term1TeachingDays'   => ['nullable', 'numeric', 'min:0'],
            'term1ExamDays'       => ['nullable', 'numeric', 'min:0'],
            'term2Dates'          => ['nullable', 'string', 'max:100'],
            'term2TotalDays'      => ['nullable', 'numeric', 'min:0'],
            'term2TeachingDays'   => ['nullable', 'numeric', 'min:0'],
            'term2ExamDays'       => ['nullable', 'numeric', 'min:0'],
            'examSeries1Title'    => ['nullable', 'string', 'max:100'],
            'examSeries1Sub'      => ['nullable', 'string', 'max:100'],
            'examSeries1Period'   => ['nullable', 'string', 'max:100'],
            'examSeries2Title'    => ['nullable', 'string', 'max:100'],
            'examSeries2Sub'      => ['nullable', 'string', 'max:100'],
            'examSeries2Period'   => ['nullable', 'string', 'max:100'],
            'examSeries3Title'    => ['nullable', 'string', 'max:100'],
            'examSeries3Sub'      => ['nullable', 'string', 'max:100'],
            'examSeries3Period'   => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:date'],
            'type'        => ['required', 'in:event,term,holiday,exam'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'    => ['boolean'],
            'is_tentative' => ['boolean'],
        ];
    }

    public function save(): void
    {
        if (! $this->calendarId) {
            $this->addError('calendarId', 'Select a calendar before adding entries.');
            return;
        }

        $this->validate();

        $data = [
            'calendar_id' => $this->calendarId,
            'title'       => $this->title,
            'date'        => $this->date,
            'end_date'    => $this->end_date ?: null,
            'type'        => $this->type,
            'description' => $this->description ?: null,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
            'is_tentative' => $this->is_tentative,
        ];

        if ($this->editingId) {
            DigitalServiceCalendarEntry::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Entry updated.');
        } else {
            DigitalServiceCalendarEntry::create($data);
            $this->dispatch('toast', message: 'Entry added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $entry = DigitalServiceCalendarEntry::findOrFail($id);
        $this->editingId   = $entry->id;
        $this->title       = $entry->title;
        $this->date        = $entry->date->format('Y-m-d');
        $this->end_date    = $entry->end_date?->format('Y-m-d') ?? '';
        $this->type        = $entry->type;
        $this->description = $entry->description ?? '';
        $this->sort_order   = $entry->sort_order;
        $this->is_active    = $entry->is_active;
        $this->is_tentative = $entry->is_tentative;
        $this->showEntryForm = true;
    }

    public function delete(int $id): void
    {
        DigitalServiceCalendarEntry::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Entry deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'date', 'end_date', 'description', 'editingId']);
        $this->type         = 'event';
        $this->is_active    = true;
        $this->is_tentative = false;
        $this->sort_order   = 0;
        $this->showEntryForm = false;
    }

    private function resetCalendarForm(): void
    {
        $this->reset(['calendarTitle', 'calendarDescription', 'calendarEditingId',
            'statTeachingDays', 'statExamDays', 'statReportPrepDays',
            'statTeacherPdDays', 'statNonTeachingDays', 'statTotalDays',
            'term1Dates', 'term1TotalDays', 'term1TeachingDays', 'term1ExamDays',
            'term2Dates', 'term2TotalDays', 'term2TeachingDays', 'term2ExamDays',
            'examSeries1Title', 'examSeries1Sub', 'examSeries1Period',
            'examSeries2Title', 'examSeries2Sub', 'examSeries2Period',
            'examSeries3Title', 'examSeries3Sub', 'examSeries3Period']);
        $this->calendarYear      = (int) date('Y');
        $this->calendarSortOrder = 0;
        $this->calendarIsActive  = false;
        $this->showCalendarForm  = false;
    }

    public function render()
    {
        $calendars = DigitalServiceCalendar::withCount('entries')->orderBy('sort_order')->orderBy('year', 'desc')->get();
        $currentCalendar = $this->calendarId
            ? DigitalServiceCalendar::find($this->calendarId)
            : null;

        $entries = $this->calendarId
            ? DigitalServiceCalendarEntry::where('calendar_id', $this->calendarId)
                ->orderBy('date')->orderBy('id')
                ->paginate(15)
            : DigitalServiceCalendarEntry::whereRaw('1 = 0')->paginate(15);

        return view('livewire.cms.digital-services.calendar-entries-index', [
            'calendars'       => $calendars,
            'currentCalendar' => $currentCalendar,
            'entries'         => $entries,
        ])->layout('layouts.app', ['title' => 'Digital Services — Academic Calendar']);
    }
}
