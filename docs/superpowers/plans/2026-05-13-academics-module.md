# Academics Module Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement the Academics module — two DB tables, two CMS components (overview singleton + levels CRUD), and a public `/academics` website page matching `docs/static_htmls/academics.html`.

**Architecture:** Follows the exact patterns of existing modules: `AcademicsOverview` uses the `Mission` singleton pattern (`MissionEdit`), `AcademicLevel` uses the `Achievement` CRUD pattern (`AcademicLevelsIndex`). The website page is a read-only Livewire component under `layouts.web`. Subject/target/stream fields are JSON arrays of plain strings managed via add/remove repeaters.

**Tech Stack:** Laravel 11, Livewire 4, Tailwind CSS

---

## File Map

| Action | Path |
|---|---|
| Create | `database/migrations/2026_05_13_000002_create_academics_overview_table.php` |
| Create | `database/migrations/2026_05_13_000003_create_academic_levels_table.php` |
| Create | `app/Models/AcademicsOverview.php` |
| Create | `app/Models/AcademicLevel.php` |
| Create | `app/Livewire/Cms/Academics/AcademicsOverviewEdit.php` |
| Create | `resources/views/livewire/cms/academics/overview-edit.blade.php` |
| Create | `app/Livewire/Cms/Academics/AcademicLevelsIndex.php` |
| Create | `resources/views/livewire/cms/academics/levels-index.blade.php` |
| Create | `app/Livewire/Website/Academics.php` |
| Create | `resources/views/livewire/website/academics.blade.php` |
| Modify | `routes/web.php` |
| Modify | `resources/views/layouts/partials/sidebar.blade.php` |
| Modify | `resources/views/layouts/partials/nav.blade.php` |
| Copy | `docs/static_site/cambridge-international-education.svg` → `public/images/cambridge-international-education.svg` |
| Copy | `docs/static_site/pearson-edexcel.png` → `public/images/pearson-edexcel.png` |
| Create | `tests/Feature/AcademicsModuleTest.php` |

---

### Task 1: Migrations + Models

**Files:**
- Create: `database/migrations/2026_05_13_000002_create_academics_overview_table.php`
- Create: `database/migrations/2026_05_13_000003_create_academic_levels_table.php`
- Create: `app/Models/AcademicsOverview.php`
- Create: `app/Models/AcademicLevel.php`

- [ ] **Step 1: Create academics_overview migration**

Create `database/migrations/2026_05_13_000002_create_academics_overview_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academics_overview', function (Blueprint $table) {
            $table->id();
            $table->longText('text')->nullable();
            $table->string('curriculum')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academics_overview');
    }
};
```

- [ ] **Step 2: Create academic_levels migration**

Create `database/migrations/2026_05_13_000003_create_academic_levels_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('abbreviation', 10);
            $table->string('label');
            $table->string('age_range', 100);
            $table->string('year_groups');
            $table->string('lead_teacher');
            $table->string('lead_teacher_photo_path')->nullable();
            $table->json('subjects')->nullable();
            $table->json('targets')->nullable();
            $table->json('streams')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_levels');
    }
};
```

- [ ] **Step 3: Run migrations**

```bash
php artisan migrate
```

Expected: `academics_overview` and `academic_levels` tables created, no errors.

- [ ] **Step 4: Create AcademicsOverview model**

Create `app/Models/AcademicsOverview.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicsOverview extends Model
{
    protected $table = 'academics_overview';

    protected $fillable = ['text', 'curriculum'];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
```

- [ ] **Step 5: Create AcademicLevel model**

Create `app/Models/AcademicLevel.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AcademicLevel extends Model
{
    protected $fillable = [
        'sort_order', 'abbreviation', 'label', 'age_range', 'year_groups',
        'lead_teacher', 'lead_teacher_photo_path',
        'subjects', 'targets', 'streams', 'is_active',
    ];

    protected $casts = [
        'subjects'  => 'array',
        'targets'   => 'array',
        'streams'   => 'array',
        'is_active' => 'boolean',
    ];

    public function getLeadTeacherPhotoUrlAttribute(): ?string
    {
        if (! $this->lead_teacher_photo_path) {
            return null;
        }
        return Storage::url($this->lead_teacher_photo_path);
    }

    public function getInitialsAttribute(): string
    {
        $name = preg_replace('/^(Mr|Ms|Mrs|Dr)\.\s+/', '', $this->lead_teacher);
        return collect(explode(' ', $name))
            ->map(fn ($p) => strtoupper(substr($p, 0, 1)))
            ->take(2)
            ->join('');
    }
}
```

- [ ] **Step 6: Commit**

```bash
git add database/migrations/2026_05_13_000002_create_academics_overview_table.php \
        database/migrations/2026_05_13_000003_create_academic_levels_table.php \
        app/Models/AcademicsOverview.php \
        app/Models/AcademicLevel.php
git commit -m "feat: add academics overview and levels migrations and models"
```

---

### Task 2: CMS Overview Component + Blade

**Files:**
- Create: `app/Livewire/Cms/Academics/AcademicsOverviewEdit.php`
- Create: `resources/views/livewire/cms/academics/overview-edit.blade.php`

- [ ] **Step 1: Create AcademicsOverviewEdit component**

Create `app/Livewire/Cms/Academics/AcademicsOverviewEdit.php`:

```php
<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicsOverview;
use Livewire\Component;

class AcademicsOverviewEdit extends Component
{
    public string $text = '';
    public string $curriculum = '';

    public function mount(): void
    {
        $record = AcademicsOverview::singleton();
        $this->text       = $record->text ?? '';
        $this->curriculum = $record->curriculum ?? '';
    }

    protected function rules(): array
    {
        return [
            'text'       => ['nullable', 'string'],
            'curriculum' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        AcademicsOverview::singleton()->update([
            'text'       => $this->text ?: null,
            'curriculum' => $this->curriculum ?: null,
        ]);
        $this->dispatch('toast', message: 'Overview saved.');
    }

    public function render()
    {
        return view('livewire.cms.academics.overview-edit')
            ->layout('layouts.app', ['title' => 'Academics — Overview']);
    }
}
```

- [ ] **Step 2: Create overview-edit blade**

Create `resources/views/livewire/cms/academics/overview-edit.blade.php`:

```blade
<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Academics</h1>
        <p class="admin-muted">Manage the overview text and curriculum line shown at the top of the Academics page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <h3 class="admin-section-title mb-4">Overview</h3>
        <form wire:submit="save" class="space-y-4">

            <div>
                <label class="mb-1.5 block admin-label">Overview text</label>
                <textarea wire:model="text" rows="5"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Describe the school's academic approach..."></textarea>
                @error('text') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Curriculum line <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="curriculum"
                       placeholder="e.g. Maldives National Curriculum · Cambridge · Pearson Edexcel"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('curriculum') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    Save overview
                </button>
            </div>

        </form>
    </div>

</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Cms/Academics/AcademicsOverviewEdit.php \
        resources/views/livewire/cms/academics/overview-edit.blade.php
git commit -m "feat: add CMS AcademicsOverviewEdit component and blade"
```

---

### Task 3: CMS AcademicLevelsIndex Component

**Files:**
- Create: `app/Livewire/Cms/Academics/AcademicLevelsIndex.php`

- [ ] **Step 1: Create AcademicLevelsIndex component**

Create `app/Livewire/Cms/Academics/AcademicLevelsIndex.php`:

```php
<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicLevel;
use Livewire\Component;
use Livewire\WithFileUploads;

class AcademicLevelsIndex extends Component
{
    use WithFileUploads;

    public string $abbreviation = '';
    public string $label = '';
    public string $age_range = '';
    public string $year_groups = '';
    public string $lead_teacher = '';
    public $lead_teacher_photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public array $subjects = [];
    public array $targets = [];
    public array $streams = [];
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'abbreviation'      => ['required', 'string', 'max:10'],
            'label'             => ['required', 'string', 'max:255'],
            'age_range'         => ['required', 'string', 'max:100'],
            'year_groups'       => ['required', 'string', 'max:255'],
            'lead_teacher'      => ['required', 'string', 'max:255'],
            'lead_teacher_photo'=> ['nullable', 'image', 'max:2048'],
            'subjects'          => ['nullable', 'array'],
            'subjects.*'        => ['nullable', 'string', 'max:255'],
            'targets'           => ['nullable', 'array'],
            'targets.*'         => ['nullable', 'string', 'max:255'],
            'streams'           => ['nullable', 'array'],
            'streams.*'         => ['nullable', 'string', 'max:255'],
            'sort_order'        => ['integer', 'min:0'],
            'is_active'         => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'abbreviation' => strtoupper(trim($this->abbreviation)),
            'label'        => $this->label,
            'age_range'    => $this->age_range,
            'year_groups'  => $this->year_groups,
            'lead_teacher' => $this->lead_teacher,
            'subjects'     => array_values(array_filter($this->subjects, fn ($s) => trim($s) !== '')) ?: null,
            'targets'      => array_values(array_filter($this->targets,  fn ($t) => trim($t) !== '')) ?: null,
            'streams'      => array_values(array_filter($this->streams,  fn ($s) => trim($s) !== '')) ?: null,
            'sort_order'   => $this->sort_order,
            'is_active'    => $this->is_active,
        ];

        if ($this->lead_teacher_photo) {
            $data['lead_teacher_photo_path'] = $this->lead_teacher_photo->store('academics', 'public');
        } elseif ($this->photoRemoved) {
            $data['lead_teacher_photo_path'] = null;
        }

        if ($this->editingId) {
            AcademicLevel::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Level updated.');
        } else {
            AcademicLevel::create($data);
            $this->dispatch('toast', message: 'Level added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $level = AcademicLevel::findOrFail($id);
        $this->editingId           = $level->id;
        $this->abbreviation        = $level->abbreviation;
        $this->label               = $level->label;
        $this->age_range           = $level->age_range;
        $this->year_groups         = $level->year_groups;
        $this->lead_teacher        = $level->lead_teacher;
        $this->existing_photo      = $level->lead_teacher_photo_path;
        $this->photoRemoved        = false;
        $this->subjects            = $level->subjects ?? [];
        $this->targets             = $level->targets ?? [];
        $this->streams             = $level->streams ?? [];
        $this->sort_order          = $level->sort_order;
        $this->is_active           = $level->is_active;
    }

    public function delete(int $id): void
    {
        AcademicLevel::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Level deleted.');
    }

    public function removePhoto(): void
    {
        $this->lead_teacher_photo = null;
        $this->existing_photo     = null;
        $this->photoRemoved       = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function addSubject(): void   { $this->subjects[] = ''; }
    public function removeSubject(int $i): void { array_splice($this->subjects, $i, 1); }

    public function addTarget(): void    { $this->targets[] = ''; }
    public function removeTarget(int $i): void  { array_splice($this->targets, $i, 1); }

    public function addStream(): void    { $this->streams[] = ''; }
    public function removeStream(int $i): void  { array_splice($this->streams, $i, 1); }

    private function resetForm(): void
    {
        $this->reset([
            'abbreviation', 'label', 'age_range', 'year_groups', 'lead_teacher',
            'lead_teacher_photo', 'existing_photo', 'photoRemoved',
            'subjects', 'targets', 'streams', 'sort_order', 'editingId',
        ]);
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.academics.levels-index', [
            'levels' => AcademicLevel::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Academics — Levels']);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Cms/Academics/AcademicLevelsIndex.php
git commit -m "feat: add CMS AcademicLevelsIndex Livewire component"
```

---

### Task 4: CMS AcademicLevelsIndex Blade

**Files:**
- Create: `resources/views/livewire/cms/academics/levels-index.blade.php`

- [ ] **Step 1: Create levels-index blade**

Create `resources/views/livewire/cms/academics/levels-index.blade.php`:

```blade
<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Academic Levels</h1>
        <p class="admin-muted">Manage key stage cards shown on the public Academics page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit level' : 'Add level' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Abbreviation + Label --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Abbreviation</label>
                    <input type="text" wire:model="abbreviation" placeholder="e.g. KS1"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('abbreviation') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Label</label>
                    <input type="text" wire:model="label" placeholder="e.g. Key Stage 1"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('label') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Age range + Year groups --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Age range</label>
                    <input type="text" wire:model="age_range" placeholder="e.g. Ages 6 – 8"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('age_range') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year groups / Grades</label>
                    <input type="text" wire:model="year_groups" placeholder="e.g. Grade 1 – Grade 3"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year_groups') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Lead teacher + photo --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Lead teacher</label>
                    <input type="text" wire:model="lead_teacher" placeholder="e.g. Ms. Aishath Shifna"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('lead_teacher') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Photo <span class="text-zinc-400">(optional)</span></label>
                    @if($lead_teacher_photo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ $lead_teacher_photo->temporaryUrl() }}" alt="Preview" class="h-12 w-12 rounded-full object-cover">
                            <button type="button" wire:click="removePhoto"
                                    class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @elseif($existing_photo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ Storage::url($existing_photo) }}" alt="Photo" class="h-12 w-12 rounded-full object-cover">
                            <button type="button" wire:click="removePhoto"
                                    class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @endif
                    <input type="file" wire:model.live="lead_teacher_photo" accept="image/*"
                           class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                    @error('lead_teacher_photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Subjects --}}
            <div>
                <label class="mb-1.5 block admin-label">Subjects</label>
                <div class="space-y-2">
                    @foreach($subjects as $i => $subject)
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="subjects.{{ $i }}" placeholder="e.g. Mathematics"
                                   class="h-9 flex-1 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <button type="button" wire:click="removeSubject({{ $i }})"
                                    class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md border border-zinc-200 text-zinc-400 hover:border-red-200 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @error("subjects.{$i}") <p class="admin-form-error">{{ $message }}</p> @enderror
                    @endforeach
                </div>
                <button type="button" wire:click="addSubject"
                        class="mt-2 inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm hover:bg-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add subject
                </button>
            </div>

            {{-- Targets --}}
            <div>
                <label class="mb-1.5 block admin-label">Targets</label>
                <div class="space-y-2">
                    @foreach($targets as $i => $target)
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="targets.{{ $i }}" placeholder="e.g. Reading fluency"
                                   class="h-9 flex-1 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <button type="button" wire:click="removeTarget({{ $i }})"
                                    class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md border border-zinc-200 text-zinc-400 hover:border-red-200 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @error("targets.{$i}") <p class="admin-form-error">{{ $message }}</p> @enderror
                    @endforeach
                </div>
                <button type="button" wire:click="addTarget"
                        class="mt-2 inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm hover:bg-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add target
                </button>
            </div>

            {{-- Streams --}}
            <div>
                <label class="mb-1.5 block admin-label">Streams <span class="text-zinc-400">(optional, e.g. KS4/KS5 only)</span></label>
                <div class="space-y-2">
                    @foreach($streams as $i => $stream)
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="streams.{{ $i }}" placeholder="e.g. Science Stream"
                                   class="h-9 flex-1 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <button type="button" wire:click="removeStream({{ $i }})"
                                    class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md border border-zinc-200 text-zinc-400 hover:border-red-200 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @error("streams.{$i}") <p class="admin-form-error">{{ $message }}</p> @enderror
                    @endforeach
                </div>
                <button type="button" wire:click="addStream"
                        class="mt-2 inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm hover:bg-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add stream
                </button>
            </div>

            {{-- Sort order + Active --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('sort_order') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Visibility</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update level' : 'Add level' }}
                </button>
                @if($editingId)
                    <button type="button" wire:click="cancel"
                            class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                        Cancel
                    </button>
                @endif
            </div>

        </form>
    </div>

    {{-- Levels list --}}
    @php
    $stageColors = [
        'FS'  => 'bg-rose-100 text-rose-700',
        'KS1' => 'bg-sky-100 text-sky-700',
        'KS2' => 'bg-emerald-100 text-emerald-700',
        'KS3' => 'bg-violet-100 text-violet-700',
        'KS4' => 'bg-amber-100 text-amber-700',
        'KS5' => 'bg-slate-100 text-slate-700',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Stage</th>
            <th class="admin-table-heading">Label</th>
            <th class="admin-table-heading">Ages / Grades</th>
            <th class="admin-table-heading">Lead teacher</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($levels as $level)
                <tr>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $level->abbreviation }}
                        </span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $level->label }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">
                        {{ $level->age_range }}<br>{{ $level->year_groups }}
                    </td>
                    <td class="admin-table-cell">
                        <div class="flex items-center gap-2">
                            @if($level->lead_teacher_photo_path)
                                <img src="{{ $level->lead_teacher_photo_url }}" alt="{{ $level->lead_teacher }}" class="h-7 w-7 rounded-full object-cover flex-shrink-0">
                            @else
                                <div class="h-7 w-7 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $level->initials }}
                                </div>
                            @endif
                            <span class="text-xs text-zinc-700">{{ $level->lead_teacher }}</span>
                        </div>
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $level->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $level->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $level->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $level->id }})" wire:confirm="Delete this level?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No levels yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($levels as $level)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold flex-shrink-0 {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $level->abbreviation }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $level->label }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $level->age_range }} · {{ $level->lead_teacher }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $level->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $level->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No levels yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/cms/academics/levels-index.blade.php
git commit -m "feat: add CMS academic levels blade view"
```

---

### Task 5: Routes, Sidebar, Nav + Partner Logos

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/partials/sidebar.blade.php`
- Modify: `resources/views/layouts/partials/nav.blade.php`
- Copy: `docs/static_site/cambridge-international-education.svg` → `public/images/cambridge-international-education.svg`
- Copy: `docs/static_site/pearson-edexcel.png` → `public/images/pearson-edexcel.png`

- [ ] **Step 1: Update routes/web.php**

Read `routes/web.php` first. Add imports at the top:

```php
use App\Livewire\Cms\Academics\AcademicsOverviewEdit;
use App\Livewire\Cms\Academics\AcademicLevelsIndex;
use App\Livewire\Website\Academics;
```

Inside the `auth` middleware group, after the events module routes, add:

```php
    // CMS — academics module
    Route::get('/cms/academics/overview', AcademicsOverviewEdit::class)->name('cms.academics.overview');
    Route::get('/cms/academics/levels', AcademicLevelsIndex::class)->name('cms.academics.levels');
```

In the public website routes, replace:
```php
Route::get('/academics', fn () => 'Academics')->name('academics');
```
with:
```php
Route::get('/academics', Academics::class)->name('academics.index');
```

- [ ] **Step 2: Update sidebar.blade.php**

Read `resources/views/layouts/partials/sidebar.blade.php`. In the CMS group items array, after the Events item, add:

```php
[
    'label'        => 'Academics',
    'route'        => 'cms.academics.overview',
    'href'         => route('cms.academics.overview'),
    'activeRoutes' => ['cms.academics.'],
    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>',
],
```

- [ ] **Step 3: Update nav.blade.php — fix route names**

Read `resources/views/layouts/partials/nav.blade.php`. The nav currently references `route('academics')` and `routeIs('academics')` in two places (desktop and mobile). Update both to:

```php
route('academics.index')
```
and:
```php
request()->routeIs('academics*')
```

- [ ] **Step 4: Copy partner logo assets**

```bash
cp docs/static_site/cambridge-international-education.svg public/images/cambridge-international-education.svg
cp docs/static_site/pearson-edexcel.png public/images/pearson-edexcel.png
```

- [ ] **Step 5: Verify routes**

```bash
php artisan route:list --name=academics
```

Expected: `academics.index` (public), `cms.academics.overview`, `cms.academics.levels` (both with `auth,verified` middleware).

- [ ] **Step 6: Commit**

```bash
git add routes/web.php \
        resources/views/layouts/partials/sidebar.blade.php \
        resources/views/layouts/partials/nav.blade.php \
        public/images/cambridge-international-education.svg \
        public/images/pearson-edexcel.png
git commit -m "feat: register academics CMS and website routes, add sidebar nav item, copy partner logos"
```

---

### Task 6: Website Academics Component + Blade

**Files:**
- Create: `app/Livewire/Website/Academics.php`
- Create: `resources/views/livewire/website/academics.blade.php`

Design source: `docs/static_htmls/academics.html`

- [ ] **Step 1: Create Academics component**

Create `app/Livewire/Website/Academics.php`:

```php
<?php

namespace App\Livewire\Website;

use App\Models\AcademicLevel;
use App\Models\AcademicsOverview;
use Livewire\Component;

class Academics extends Component
{
    public function render()
    {
        return view('livewire.website.academics', [
            'overview' => AcademicsOverview::singleton(),
            'levels'   => AcademicLevel::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ])->layout('layouts.web');
    }
}
```

- [ ] **Step 2: Create academics blade**

Create `resources/views/livewire/website/academics.blade.php`:

```blade
<div>

    @php
    $stageColors = [
        'FS'  => 'bg-rose-100 text-rose-700',
        'KS1' => 'bg-sky-100 text-sky-700',
        'KS2' => 'bg-emerald-100 text-emerald-700',
        'KS3' => 'bg-violet-100 text-violet-700',
        'KS4' => 'bg-amber-100 text-amber-700',
        'KS5' => 'bg-slate-100 text-slate-700',
    ];
    @endphp

    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2" data-reveal="fade">Our Curriculum</p>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3" data-reveal="left">Academics</h1>
                    @if($overview->text)
                        <p class="text-slate-500 max-w-2xl text-sm leading-relaxed" data-reveal="fade">{{ $overview->text }}</p>
                    @endif
                    @if($overview->curriculum)
                        <span class="mt-3 inline-block text-xs font-semibold bg-sky-100 text-sky-700 px-3 py-1 rounded-full" data-reveal="fade">{{ $overview->curriculum }}</span>
                    @endif
                </div>
                <div class="lg:ml-auto grid grid-cols-2 gap-3 sm:flex sm:items-center" data-reveal="right">
                    <div class="h-20 sm:h-24 w-full sm:w-52 rounded-2xl border border-slate-200 bg-white px-4 py-3 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/cambridge-international-education.svg') }}" alt="Cambridge International Education" class="max-h-12 w-full object-contain">
                    </div>
                    <div class="h-20 sm:h-24 w-full sm:w-52 rounded-2xl border border-slate-200 bg-white px-4 py-3 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/pearson-edexcel.png') }}" alt="Pearson Edexcel" class="max-h-12 w-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Level cards --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($levels->isEmpty())
            <div class="text-center py-20 text-slate-400">
                <p class="text-lg font-semibold">No academic levels published yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($levels as $level)
                <article class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow" data-reveal="scale" style="--reveal-delay: {{ $loop->index * 60 }}ms">

                    {{-- Header: badge + age --}}
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <span class="inline-flex text-xs font-bold uppercase tracking-widest px-2.5 py-1 rounded-full {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">{{ $level->abbreviation }}</span>
                            <h2 class="text-xl font-black text-slate-900 mt-3">{{ $level->label }}</h2>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Age group</p>
                            <p class="text-xs font-semibold text-slate-700">{{ $level->age_range }}</p>
                        </div>
                    </div>

                    {{-- Grades + Lead teacher --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Grades</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $level->year_groups }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Leading teacher</p>
                            <div class="flex items-center gap-2">
                                @if($level->lead_teacher_photo_path)
                                    <img src="{{ $level->lead_teacher_photo_url }}" alt="{{ $level->lead_teacher }}" class="w-8 h-8 rounded-full flex-shrink-0 object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $level->initials }}
                                    </div>
                                @endif
                                <p class="text-sm font-semibold text-slate-900 leading-tight">{{ $level->lead_teacher }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Streams (optional) --}}
                    @if($level->streams && count($level->streams) > 0)
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach($level->streams as $stream)
                                <span class="text-xs font-semibold bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full">{{ $stream }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Subjects --}}
                    @if($level->subjects && count($level->subjects) > 0)
                        <div class="mb-4">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2">Subjects</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($level->subjects as $subject)
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg">{{ $subject }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Targets --}}
                    @if($level->targets && count($level->targets) > 0)
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2">Targets</p>
                            <ul class="space-y-1.5">
                                @foreach($level->targets as $target)
                                    <li class="text-xs text-slate-600 flex gap-2">
                                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-rose-400 flex-shrink-0"></span>
                                        {{ $target }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </article>
                @endforeach
            </div>
        @endif
    </div>

</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Website/Academics.php resources/views/livewire/website/academics.blade.php
git commit -m "feat: add Website Academics page"
```

---

### Task 7: Tests + Seed/Verify

**Files:**
- Create: `tests/Feature/AcademicsModuleTest.php`

- [ ] **Step 1: Write tests**

Create `tests/Feature/AcademicsModuleTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\AcademicLevel;
use App\Models\AcademicsOverview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_academics_page_returns_ok(): void
    {
        $this->get('/academics')->assertOk();
    }

    public function test_cms_academics_overview_requires_auth(): void
    {
        $this->get('/cms/academics/overview')->assertRedirect('/login');
    }

    public function test_cms_academics_levels_requires_auth(): void
    {
        $this->get('/cms/academics/levels')->assertRedirect('/login');
    }

    public function test_academics_page_shows_active_levels(): void
    {
        AcademicLevel::create([
            'abbreviation' => 'KS1',
            'label'        => 'Key Stage 1',
            'age_range'    => 'Ages 6 – 8',
            'year_groups'  => 'Grade 1 – Grade 3',
            'lead_teacher' => 'Ms. Test Teacher',
            'is_active'    => true,
        ]);

        $this->get('/academics')->assertSee('Key Stage 1');
    }

    public function test_academics_page_hides_inactive_levels(): void
    {
        AcademicLevel::create([
            'abbreviation' => 'KS2',
            'label'        => 'Key Stage 2',
            'age_range'    => 'Ages 9 – 11',
            'year_groups'  => 'Grade 4 – Grade 6',
            'lead_teacher' => 'Mr. Hidden',
            'is_active'    => false,
        ]);

        $this->get('/academics')->assertDontSee('Key Stage 2');
    }

    public function test_academics_page_shows_overview_text(): void
    {
        AcademicsOverview::create([
            'id'         => 1,
            'text'       => 'World-class education.',
            'curriculum' => 'Cambridge · Pearson Edexcel',
        ]);

        $this->get('/academics')->assertSee('World-class education.');
    }
}
```

- [ ] **Step 2: Run tests**

```bash
php artisan test tests/Feature/AcademicsModuleTest.php --stop-on-failure
```

Expected: 5 tests pass.

- [ ] **Step 3: Seed the academic levels seeder (if it exists)**

```bash
php artisan db:seed --class=AcademicLevelSeeder 2>/dev/null || echo "No seeder found — add levels via CMS"
```

- [ ] **Step 4: Verify routes compile**

```bash
php artisan route:list --name=academics
```

Expected:
```
GET|HEAD  academics              academics.index      › App\Livewire\Website\Academics
GET|HEAD  cms/academics/levels   cms.academics.levels › App\Livewire\Cms\Academics\AcademicLevels... [auth, verified]
GET|HEAD  cms/academics/overview cms.academics.overview › App\Livewire\Cms\Academics\Academics... [auth, verified]
```

- [ ] **Step 5: Commit**

```bash
git add tests/Feature/AcademicsModuleTest.php
git commit -m "test: add AcademicsModule feature tests"
```
