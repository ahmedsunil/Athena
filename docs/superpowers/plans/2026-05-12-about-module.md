# About Module Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the About page CMS (5 tabs: Mission, Leadership, Founding Members, History, Achievements) and the public `/about` Livewire page that renders all data from those models.

**Architecture:** Five new Eloquent models with migrations; five CMS Livewire components sharing a `cms-about-tabs` nav partial; one website Livewire component with Livewire-state-driven achievement filtering. Follows identical patterns to existing `HomeSlides`, `HomeTestimonials`, `SchoolProfileEdit` modules.

**Tech Stack:** Laravel 11, Livewire 3, Tailwind CSS (Athena admin design system tokens), PostgreSQL/MySQL.

---

## File Map

### New migrations
- `database/migrations/2026_05_12_000001_create_missions_table.php`
- `database/migrations/2026_05_12_000002_create_leadership_members_table.php`
- `database/migrations/2026_05_12_000003_create_founding_members_table.php`
- `database/migrations/2026_05_12_000004_create_history_sections_table.php`
- `database/migrations/2026_05_12_000005_create_achievements_table.php`

### New models
- `app/Models/Mission.php`
- `app/Models/LeadershipMember.php`
- `app/Models/FoundingMember.php`
- `app/Models/HistorySection.php`
- `app/Models/Achievement.php`

### New CMS Livewire components
- `app/Livewire/Cms/About/MissionEdit.php`
- `app/Livewire/Cms/About/LeadershipIndex.php`
- `app/Livewire/Cms/About/FoundingMembersIndex.php`
- `app/Livewire/Cms/About/HistorySectionsIndex.php`
- `app/Livewire/Cms/About/AchievementsIndex.php`

### New CMS views
- `resources/views/layouts/partials/cms-about-tabs.blade.php`
- `resources/views/livewire/cms/about/mission-edit.blade.php`
- `resources/views/livewire/cms/about/leadership-index.blade.php`
- `resources/views/livewire/cms/about/founding-members-index.blade.php`
- `resources/views/livewire/cms/about/history-sections-index.blade.php`
- `resources/views/livewire/cms/about/achievements-index.blade.php`

### New website Livewire component + view
- `app/Livewire/Website/About.php`
- `resources/views/livewire/website/about.blade.php`

### Modified files
- `routes/web.php` — add 5 CMS routes + wire `/about` to `Website\About`
- `resources/views/layouts/partials/sidebar.blade.php` — add "About" CMS nav item

---

## Task 1: Migrations and Models

**Files:**
- Create: all 5 migration files listed above
- Create: all 5 model files listed above

- [ ] **Step 1: Create missions migration**

```php
// database/migrations/2026_05_12_000001_create_missions_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('missions', function (Blueprint $table) {
            $table->id();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('missions');
    }
};
```

- [ ] **Step 2: Create leadership_members migration**

```php
// database/migrations/2026_05_12_000002_create_leadership_members_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leadership_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leadership_members');
    }
};
```

- [ ] **Step 3: Create founding_members migration**

```php
// database/migrations/2026_05_12_000003_create_founding_members_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('founding_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subject')->nullable();
            $table->text('tribute')->nullable();
            $table->string('photo_path')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('founding_members');
    }
};
```

- [ ] **Step 4: Create history_sections migration**

```php
// database/migrations/2026_05_12_000004_create_history_sections_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('history_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('year_label')->nullable(); // e.g. "1993 – 1995"
            $table->text('body'); // plain text, rendered as paragraphs
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('history_sections');
    }
};
```

- [ ] **Step 5: Create achievements migration**

```php
// database/migrations/2026_05_12_000005_create_achievements_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['school', 'students', 'staff']);
            $table->unsignedSmallInteger('year');
            $table->text('description')->nullable();
            $table->string('award')->nullable();
            $table->string('event_name')->nullable();
            $table->string('person_name')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
```

- [ ] **Step 6: Run migrations**

```bash
php artisan migrate
```

Expected: 5 new tables created with no errors.

- [ ] **Step 7: Create Mission model**

```php
// app/Models/Mission.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    protected $fillable = ['mission', 'vision'];

    public static function singleton(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
```

- [ ] **Step 8: Create LeadershipMember model**

```php
// app/Models/LeadershipMember.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LeadershipMember extends Model
{
    protected $fillable = [
        'name', 'role', 'bio', 'photo_path', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public static function resolvePhotoUrl(?string $path): ?string
    {
        if (! $path) return null;
        if (Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePhotoUrl($this->photo_path);
    }
}
```

- [ ] **Step 9: Create FoundingMember model**

```php
// app/Models/FoundingMember.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FoundingMember extends Model
{
    protected $fillable = [
        'name', 'subject', 'tribute', 'photo_path', 'sort_order',
    ];

    public static function resolvePhotoUrl(?string $path): ?string
    {
        if (! $path) return null;
        if (Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePhotoUrl($this->photo_path);
    }
}
```

- [ ] **Step 10: Create HistorySection model**

```php
// app/Models/HistorySection.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorySection extends Model
{
    protected $fillable = ['title', 'year_label', 'body', 'sort_order'];
}
```

- [ ] **Step 11: Create Achievement model**

```php
// app/Models/Achievement.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Achievement extends Model
{
    protected $fillable = [
        'title', 'category', 'year', 'description',
        'award', 'event_name', 'person_name', 'photo_path',
        'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public static function resolvePhotoUrl(?string $path): ?string
    {
        if (! $path) return null;
        if (Str::startsWith($path, ['http://', 'https://'])) return $path;
        return Storage::url($path);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return self::resolvePhotoUrl($this->photo_path);
    }
}
```

- [ ] **Step 12: Commit**

```bash
git add database/migrations/2026_05_12_* app/Models/Mission.php app/Models/LeadershipMember.php app/Models/FoundingMember.php app/Models/HistorySection.php app/Models/Achievement.php
git commit -m "feat: add about module migrations and models"
```

---

## Task 2: CMS Tabs Partial + Routes + Sidebar

**Files:**
- Create: `resources/views/layouts/partials/cms-about-tabs.blade.php`
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/partials/sidebar.blade.php`

- [ ] **Step 1: Create cms-about-tabs partial**

```blade
{{-- resources/views/layouts/partials/cms-about-tabs.blade.php --}}
@php
    $currentRoute = request()->route()?->getName() ?? '';
    $aboutTabs = [
        ['label' => 'Mission & Vision',   'route' => 'cms.about.mission',           'href' => route('cms.about.mission')],
        ['label' => 'Leadership Team',    'route' => 'cms.about.leadership',         'href' => route('cms.about.leadership')],
        ['label' => 'Founding Teachers',  'route' => 'cms.about.founding-members',   'href' => route('cms.about.founding-members')],
        ['label' => 'History',            'route' => 'cms.about.history',            'href' => route('cms.about.history')],
        ['label' => 'Achievements',       'route' => 'cms.about.achievements',       'href' => route('cms.about.achievements')],
    ];
@endphp

<div>
    <h1 class="admin-page-title text-lg">About</h1>
    <p class="admin-muted">Manage the public about page content.</p>
</div>

<div class="flex items-center gap-1 pb-0 flex-wrap">
    @foreach($aboutTabs as $tab)
        @php $isActive = str_starts_with($currentRoute, $tab['route']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive ? 'text-zinc-50' : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
```

- [ ] **Step 2: Add CMS about routes to routes/web.php**

Add these imports at the top of routes/web.php with the other CMS imports:

```php
use App\Livewire\Cms\About\MissionEdit;
use App\Livewire\Cms\About\LeadershipIndex;
use App\Livewire\Cms\About\FoundingMembersIndex;
use App\Livewire\Cms\About\HistorySectionsIndex;
use App\Livewire\Cms\About\AchievementsIndex;
use App\Livewire\Website\About;
```

Add these inside the `auth` middleware group, after the existing CMS home routes:

```php
// CMS — about module
Route::get('/cms/about/mission', MissionEdit::class)->name('cms.about.mission');
Route::get('/cms/about/leadership', LeadershipIndex::class)->name('cms.about.leadership');
Route::get('/cms/about/founding-members', FoundingMembersIndex::class)->name('cms.about.founding-members');
Route::get('/cms/about/history', HistorySectionsIndex::class)->name('cms.about.history');
Route::get('/cms/about/achievements', AchievementsIndex::class)->name('cms.about.achievements');
```

Replace the existing placeholder `/about` route (outside auth middleware):

```php
Route::get('/about', About::class)->name('about');
```

- [ ] **Step 3: Add About to sidebar CMS group**

In `resources/views/layouts/partials/sidebar.blade.php`, find the CMS navGroup items array. It currently has one item ('Home'). Add an 'About' item after it:

```php
[
    'label'        => 'About',
    'route'        => 'cms.about.mission',
    'href'         => route('cms.about.mission'),
    'activeRoutes' => ['cms.about.'],
    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>',
],
```

- [ ] **Step 4: Commit**

```bash
git add resources/views/layouts/partials/cms-about-tabs.blade.php routes/web.php resources/views/layouts/partials/sidebar.blade.php
git commit -m "feat: add about CMS routes, sidebar nav, and tab partial"
```

---

## Task 3: Mission & Vision CMS Component

**Files:**
- Create: `app/Livewire/Cms/About/MissionEdit.php`
- Create: `resources/views/livewire/cms/about/mission-edit.blade.php`

- [ ] **Step 1: Create MissionEdit Livewire component**

```php
// app/Livewire/Cms/About/MissionEdit.php
<?php

namespace App\Livewire\Cms\About;

use App\Models\Mission;
use Livewire\Component;

class MissionEdit extends Component
{
    public string $mission = '';
    public string $vision = '';

    public function mount(): void
    {
        $record = Mission::singleton();
        $this->mission = $record->mission ?? '';
        $this->vision  = $record->vision ?? '';
    }

    protected function rules(): array
    {
        return [
            'mission' => ['nullable', 'string'],
            'vision'  => ['nullable', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();
        Mission::singleton()->update([
            'mission' => $this->mission,
            'vision'  => $this->vision,
        ]);
        $this->dispatch('toast', message: 'Mission & Vision saved.');
    }

    public function render()
    {
        return view('livewire.cms.about.mission-edit')
            ->layout('layouts.app', ['title' => 'Mission & Vision']);
    }
}
```

- [ ] **Step 2: Create mission-edit view**

```blade
{{-- resources/views/livewire/cms/about/mission-edit.blade.php --}}
<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <form wire:submit="save" class="space-y-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Mission Statement</h3>
                <p class="admin-caption">Displayed on the About page, Mission & Vision section.</p>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Mission</label>
                <textarea wire:model="mission" rows="4"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('mission') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Vision Statement</h3>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Vision</label>
                <textarea wire:model="vision" rows="4"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('vision') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                Save
            </button>
        </div>
    </form>

</div>
```

- [ ] **Step 3: Visit `/cms/about/mission` in browser and verify form loads, saves, and persists.**

- [ ] **Step 4: Commit**

```bash
git add app/Livewire/Cms/About/MissionEdit.php resources/views/livewire/cms/about/mission-edit.blade.php
git commit -m "feat: add Mission & Vision CMS component"
```

---

## Task 4: Leadership Team CMS Component

**Files:**
- Create: `app/Livewire/Cms/About/LeadershipIndex.php`
- Create: `resources/views/livewire/cms/about/leadership-index.blade.php`

- [ ] **Step 1: Create LeadershipIndex Livewire component**

```php
// app/Livewire/Cms/About/LeadershipIndex.php
<?php

namespace App\Livewire\Cms\About;

use App\Models\LeadershipMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class LeadershipIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $role = '';
    public string $bio = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'role'       => ['required', 'string', 'max:255'],
            'bio'        => ['nullable', 'string'],
            'is_active'  => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'role'       => $this->role,
            'bio'        => $this->bio,
            'is_active'  => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/leadership', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            LeadershipMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Member updated.');
        } else {
            LeadershipMember::create($data);
            $this->dispatch('toast', message: 'Member added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = LeadershipMember::findOrFail($id);
        $this->editingId      = $item->id;
        $this->name           = $item->name;
        $this->role           = $item->role;
        $this->bio            = $item->bio ?? '';
        $this->is_active      = $item->is_active;
        $this->sort_order     = $item->sort_order;
        $this->existing_photo = $item->photo_path;
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        LeadershipMember::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Member deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'role', 'bio', 'is_active', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.cms.about.leadership-index', [
            'members' => LeadershipMember::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Leadership Team']);
    }
}
```

- [ ] **Step 2: Create leadership-index view**

```blade
{{-- resources/views/livewire/cms/about/leadership-index.blade.php --}}
<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">Leadership Team</h1>
        <p class="admin-muted">Current school leadership shown on the About page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit member' : 'Add member' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name</label>
                    <input type="text" wire:model="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Role / Title</label>
                    <input type="text" wire:model="role" placeholder="e.g. Vice Principal, Academics"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('role') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Bio</label>
                <textarea wire:model="bio" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('bio') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Photo</label>
                @if($photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-16 w-16 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\LeadershipMember::resolvePhotoUrl($existing_photo) }}" alt="" class="h-16 w-16 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="photo" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add member' }}
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

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Role</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($members as $m)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $m->id }}</td>
                    <td class="admin-table-cell">
                        @if($m->photo_path)
                            <img src="{{ $m->photo_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $m->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $m->role }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $m->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $m->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $m->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $m->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete this member?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No members yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($members as $m)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($m->photo_path)
                        <img src="{{ $m->photo_url }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $m->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $m->role }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $m->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>
```

- [ ] **Step 3: Visit `/cms/about/leadership`, add a member, verify table row appears.**

- [ ] **Step 4: Commit**

```bash
git add app/Livewire/Cms/About/LeadershipIndex.php resources/views/livewire/cms/about/leadership-index.blade.php
git commit -m "feat: add Leadership Team CMS component"
```

---

## Task 5: Founding Members CMS Component

**Files:**
- Create: `app/Livewire/Cms/About/FoundingMembersIndex.php`
- Create: `resources/views/livewire/cms/about/founding-members-index.blade.php`

- [ ] **Step 1: Create FoundingMembersIndex Livewire component**

```php
// app/Livewire/Cms/About/FoundingMembersIndex.php
<?php

namespace App\Livewire\Cms\About;

use App\Models\FoundingMember;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoundingMembersIndex extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $subject = '';
    public string $tribute = '';
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'subject'    => ['nullable', 'string', 'max:255'],
            'tribute'    => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
            'photo'      => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'       => $this->name,
            'subject'    => $this->subject,
            'tribute'    => $this->tribute,
            'sort_order' => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/founding', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            FoundingMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Founding member updated.');
        } else {
            FoundingMember::create($data);
            $this->dispatch('toast', message: 'Founding member added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = FoundingMember::findOrFail($id);
        $this->editingId      = $item->id;
        $this->name           = $item->name;
        $this->subject        = $item->subject ?? '';
        $this->tribute        = $item->tribute ?? '';
        $this->sort_order     = $item->sort_order;
        $this->existing_photo = $item->photo_path;
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        FoundingMember::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Founding member deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'subject', 'tribute', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
    }

    public function render()
    {
        return view('livewire.cms.about.founding-members-index', [
            'members' => FoundingMember::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Founding Teachers']);
    }
}
```

- [ ] **Step 2: Create founding-members-index view**

```blade
{{-- resources/views/livewire/cms/about/founding-members-index.blade.php --}}
<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">Founding Teachers</h1>
        <p class="admin-muted">Pioneer educators honoured on the History tab.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit founding member' : 'Add founding member' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name</label>
                    <input type="text" wire:model="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Subject(s)</label>
                    <input type="text" wire:model="subject" placeholder="e.g. Mathematics &amp; Dhivehi"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('subject') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Tribute / Notes</label>
                <textarea wire:model="tribute" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('tribute') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Photo</label>
                @if($photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-16 w-16 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\FoundingMember::resolvePhotoUrl($existing_photo) }}" alt="" class="h-16 w-16 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="photo" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Sort order</label>
                <input type="number" wire:model="sort_order" min="0"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add founding member' }}
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

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Subject</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($members as $m)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $m->id }}</td>
                    <td class="admin-table-cell">
                        @if($m->photo_path)
                            <img src="{{ $m->photo_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $m->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $m->subject }}</td>
                    <td class="admin-table-cell text-zinc-400">{{ $m->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $m->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete this member?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No founding members yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($members as $m)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($m->photo_path)
                        <img src="{{ $m->photo_url }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $m->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $m->subject }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $m->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Cms/About/FoundingMembersIndex.php resources/views/livewire/cms/about/founding-members-index.blade.php
git commit -m "feat: add Founding Members CMS component"
```

---

## Task 6: History Sections CMS Component

**Files:**
- Create: `app/Livewire/Cms/About/HistorySectionsIndex.php`
- Create: `resources/views/livewire/cms/about/history-sections-index.blade.php`

- [ ] **Step 1: Create HistorySectionsIndex Livewire component**

```php
// app/Livewire/Cms/About/HistorySectionsIndex.php
<?php

namespace App\Livewire\Cms\About;

use App\Models\HistorySection;
use Livewire\Component;

class HistorySectionsIndex extends Component
{
    public string $title = '';
    public string $year_label = '';
    public string $body = '';
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'year_label' => ['nullable', 'string', 'max:50'],
            'body'       => ['required', 'string'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => $this->title,
            'year_label' => $this->year_label,
            'body'       => $this->body,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            HistorySection::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Section updated.');
        } else {
            HistorySection::create($data);
            $this->dispatch('toast', message: 'Section added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = HistorySection::findOrFail($id);
        $this->editingId  = $item->id;
        $this->title      = $item->title;
        $this->year_label = $item->year_label ?? '';
        $this->body       = $item->body;
        $this->sort_order = $item->sort_order;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        HistorySection::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Section deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'year_label', 'body', 'sort_order', 'editingId']);
    }

    public function render()
    {
        return view('livewire.cms.about.history-sections-index', [
            'sections' => HistorySection::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'History Sections']);
    }
}
```

- [ ] **Step 2: Create history-sections-index view**

```blade
{{-- resources/views/livewire/cms/about/history-sections-index.blade.php --}}
<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">History Sections</h1>
        <p class="admin-muted">Timeline sections shown on the History tab. Each section renders body text as paragraphs.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit section' : 'Add section' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title</label>
                    <input type="text" wire:model="title"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year label <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="year_label" placeholder="e.g. 1993 – 1995"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year_label') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Body</label>
                <p class="mb-1.5 text-xs text-zinc-400">Separate paragraphs with a blank line.</p>
                <textarea wire:model="body" rows="6"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('body') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Sort order</label>
                <input type="number" wire:model="sort_order" min="0"
                       class="h-9 w-32 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add section' }}
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

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($sections as $s)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $s->id }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs whitespace-nowrap">{{ $s->year_label }}</td>
                    <td class="admin-table-cell-primary">{{ $s->title }}</td>
                    <td class="admin-table-cell text-zinc-400">{{ $s->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $s->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete this section?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="admin-table-cell text-center text-zinc-400">No history sections yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($sections as $s)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $s->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $s->year_label }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $s->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Cms/About/HistorySectionsIndex.php resources/views/livewire/cms/about/history-sections-index.blade.php
git commit -m "feat: add History Sections CMS component"
```

---

## Task 7: Achievements CMS Component

**Files:**
- Create: `app/Livewire/Cms/About/AchievementsIndex.php`
- Create: `resources/views/livewire/cms/about/achievements-index.blade.php`

- [ ] **Step 1: Create AchievementsIndex Livewire component**

```php
// app/Livewire/Cms/About/AchievementsIndex.php
<?php

namespace App\Livewire\Cms\About;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\WithFileUploads;

class AchievementsIndex extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $category = 'school';
    public string $year = '';
    public string $description = '';
    public string $award = '';
    public string $event_name = '';
    public string $person_name = '';
    public bool $is_active = true;
    public int $sort_order = 0;
    public $photo = null;
    public ?string $existing_photo = null;
    public bool $photoRemoved = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'category'    => ['required', 'in:school,students,staff'],
            'year'        => ['required', 'integer', 'min:1900', 'max:2100'],
            'description' => ['nullable', 'string'],
            'award'       => ['nullable', 'string', 'max:255'],
            'event_name'  => ['nullable', 'string', 'max:255'],
            'person_name' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['boolean'],
            'sort_order'  => ['integer', 'min:0'],
            'photo'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'       => $this->title,
            'category'    => $this->category,
            'year'        => (int) $this->year,
            'description' => $this->description,
            'award'       => $this->award,
            'event_name'  => $this->event_name,
            'person_name' => $this->person_name,
            'is_active'   => $this->is_active,
            'sort_order'  => $this->sort_order,
        ];

        if ($this->photo) {
            $data['photo_path'] = $this->photo->store('about/achievements', 'public');
        } elseif ($this->photoRemoved) {
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            Achievement::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Achievement updated.');
        } else {
            Achievement::create($data);
            $this->dispatch('toast', message: 'Achievement added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = Achievement::findOrFail($id);
        $this->editingId      = $item->id;
        $this->title          = $item->title;
        $this->category       = $item->category;
        $this->year           = (string) $item->year;
        $this->description    = $item->description ?? '';
        $this->award          = $item->award ?? '';
        $this->event_name     = $item->event_name ?? '';
        $this->person_name    = $item->person_name ?? '';
        $this->is_active      = $item->is_active;
        $this->sort_order     = $item->sort_order;
        $this->existing_photo = $item->photo_path;
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->existing_photo = null;
        $this->photoRemoved = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Achievement::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Achievement deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'category', 'year', 'description', 'award',
            'event_name', 'person_name', 'is_active', 'sort_order',
            'photo', 'existing_photo', 'photoRemoved', 'editingId']);
        $this->category  = 'school';
        $this->is_active = true;
        $this->year      = (string) now()->year;
    }

    public function mount(): void
    {
        $this->year = (string) now()->year;
    }

    public function render()
    {
        return view('livewire.cms.about.achievements-index', [
            'achievements' => Achievement::orderByDesc('year')->orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Achievements']);
    }
}
```

- [ ] **Step 2: Create achievements-index view**

```blade
{{-- resources/views/livewire/cms/about/achievements-index.blade.php --}}
<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">Achievements</h1>
        <p class="admin-muted">School, student, and staff achievements displayed on the About page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit achievement' : 'Add achievement' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title</label>
                    <input type="text" wire:model="title"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Category</label>
                    <select wire:model="category"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="school">School</option>
                        <option value="students">Students</option>
                        <option value="staff">Staff</option>
                    </select>
                    @error('category') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Year</label>
                    <input type="number" wire:model="year" min="1900" max="2100"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Award</label>
                    <input type="text" wire:model="award" placeholder="e.g. 1st Place"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('award') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Event name</label>
                <input type="text" wire:model="event_name" placeholder="e.g. National Science Fair 2025"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('event_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Person name <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="person_name" placeholder="Leave blank for school-wide"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('person_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Photo <span class="text-zinc-400">(optional)</span></label>
                    @if($photo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-10 w-10 rounded-full object-cover">
                            <button type="button" wire:click="removePhoto"
                                    class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @elseif($existing_photo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ \App\Models\Achievement::resolvePhotoUrl($existing_photo) }}" alt="" class="h-10 w-10 rounded-full object-cover">
                            <button type="button" wire:click="removePhoto"
                                    class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @endif
                    <input type="file" wire:model.live="photo" accept="image/*"
                           class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                    @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Description</label>
                <textarea wire:model="description" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add achievement' }}
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

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Category</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Person</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($achievements as $a)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $a->id }}</td>
                    <td class="admin-table-cell text-zinc-500 whitespace-nowrap">{{ $a->year }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $a->category === 'students' ? 'bg-sky-100 text-sky-700' : ($a->category === 'staff' ? 'bg-violet-100 text-violet-700' : 'bg-rose-100 text-rose-700') }}">
                            {{ ucfirst($a->category) }}
                        </span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $a->title }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $a->person_name ?? '—' }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $a->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $a->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $a->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $a->id }})" wire:confirm="Delete this achievement?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No achievements yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($achievements as $a)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $a->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ ucfirst($a->category) }} · {{ $a->year }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $a->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $a->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Cms/About/AchievementsIndex.php resources/views/livewire/cms/about/achievements-index.blade.php
git commit -m "feat: add Achievements CMS component"
```

---

## Task 8: Website About Page

**Files:**
- Create: `app/Livewire/Website/About.php`
- Create: `resources/views/livewire/website/about.blade.php`

- [ ] **Step 1: Create Website\About Livewire component**

```php
// app/Livewire/Website/About.php
<?php

namespace App\Livewire\Website;

use App\Models\Achievement;
use App\Models\FoundingMember;
use App\Models\HistorySection;
use App\Models\LeadershipMember;
use App\Models\Mission;
use App\Models\SchoolProfile;
use Livewire\Component;

class About extends Component
{
    public string $activeTab = 'about';
    public string $achievementCategory = 'all';
    public string $achievementYear = 'all';

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $profile    = SchoolProfile::singleton();
        $mission    = Mission::first();
        $leadership = LeadershipMember::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $history    = HistorySection::orderBy('sort_order')->orderBy('id')->get();
        $founders   = FoundingMember::orderBy('sort_order')->orderBy('id')->get();

        $achievementsQuery = Achievement::where('is_active', true);
        if ($this->achievementCategory !== 'all') {
            $achievementsQuery->where('category', $this->achievementCategory);
        }
        if ($this->achievementYear !== 'all') {
            $achievementsQuery->where('year', (int) $this->achievementYear);
        }
        $achievements = $achievementsQuery->orderByDesc('year')->orderBy('sort_order')->get();

        $achievementYears = Achievement::where('is_active', true)
            ->selectRaw('DISTINCT year')
            ->orderByDesc('year')
            ->pluck('year');

        return view('livewire.website.about', compact(
            'profile', 'mission', 'leadership',
            'history', 'founders', 'achievements', 'achievementYears'
        ))->layout('layouts.web');
    }
}
```

- [ ] **Step 2: Create about.blade.php website view**

This is a long view. Replicate the structure from the static `docs/static_htmls/about.html` but using Blade/Livewire syntax. Key sections:

```blade
{{-- resources/views/livewire/website/about.blade.php --}}
<div>

    {{-- Hero --}}
    <section class="relative h-72 sm:h-96 overflow-hidden">
        <div class="absolute inset-0 bg-slate-900"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/50 to-transparent"></div>
        <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-end pb-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-rose-400 mb-2">About Our School</p>
                <h1 class="text-3xl sm:text-5xl font-black text-white">{{ $profile->school_name ?? config('app.name') }}</h1>
                @if($profile->motto)
                    <p class="text-slate-300 italic mt-1">"{{ $profile->motto }}"</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Tab Bar --}}
    <div class="bg-white border-b border-slate-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-0 overflow-x-auto">
                @foreach([['id'=>'about','label'=>'About Us'],['id'=>'history','label'=>'History'],['id'=>'achievements','label'=>'Achievements']] as $tab)
                    <button wire:click="switchTab('{{ $tab['id'] }}')"
                            class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors
                                   {{ $activeTab === $tab['id'] ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- About Tab --}}
    @if($activeTab === 'about')

        {{-- Mission & Vision --}}
        @if($mission && ($mission->mission || $mission->vision))
        <section class="py-20 sm:py-28 bg-stone-950 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.04]" style="background-image:repeating-linear-gradient(0deg,#f43f5e 0,#f43f5e 1px,transparent 0,transparent 48px),repeating-linear-gradient(90deg,#f43f5e 0,#f43f5e 1px,transparent 0,transparent 48px)"></div>
            <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-14">
                    <span class="text-xs font-black uppercase tracking-widest text-rose-500">Our Purpose</span>
                    <h2 class="text-4xl sm:text-5xl font-black text-white mt-2 leading-tight">Mission &amp; Vision</h2>
                    <div class="mt-5 w-14 h-0.5 bg-rose-500"></div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-stone-800">
                    @if($mission->mission)
                    <div class="relative pb-12 lg:pb-0 lg:pr-14">
                        <div class="absolute top-0 right-0 lg:right-auto lg:top-0 text-[8rem] font-black text-rose-500/8 leading-none select-none pointer-events-none">01</div>
                        <div class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-9 h-9 rounded-full border-2 border-rose-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <p class="text-xs font-black uppercase tracking-widest text-rose-500">Mission</p>
                            </div>
                            <p class="text-stone-200 text-lg leading-relaxed">{{ $mission->mission }}</p>
                        </div>
                    </div>
                    @endif
                    @if($mission->vision)
                    <div class="relative pt-12 lg:pt-0 lg:pl-14">
                        <div class="absolute top-0 right-0 text-[8rem] font-black text-sky-500/8 leading-none select-none pointer-events-none">02</div>
                        <div class="relative">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-9 h-9 rounded-full border-2 border-sky-400 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </div>
                                <p class="text-xs font-black uppercase tracking-widest text-sky-400">Vision</p>
                            </div>
                            <p class="text-stone-200 text-lg leading-relaxed">{{ $mission->vision }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        {{-- Principal's Message --}}
        @if($profile->principal_name || $profile->principal_message)
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">A Word From Leadership</p>
                    <h2 class="text-3xl font-black text-slate-900">Principal's Message</h2>
                    <div class="mt-3 mx-auto w-10 h-1 rounded-full bg-rose-500"></div>
                </div>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 sm:p-10">
                    <div class="flex flex-col sm:flex-row gap-8">
                        <div class="flex-shrink-0 flex flex-col items-center gap-3">
                            <div class="w-40 h-40 rounded-full overflow-hidden ring-4 ring-white shadow-md bg-slate-200">
                                @if($profile->principal_photo_url)
                                    <img src="{{ $profile->principal_photo_url }}" alt="{{ $profile->principal_name }}" class="w-full h-full object-cover object-top">
                                @endif
                            </div>
                            <div class="text-center">
                                <p class="font-bold text-slate-900">{{ $profile->principal_name }}</p>
                                @if($profile->principal_designation)
                                    <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $profile->principal_designation }}</p>
                                @endif
                            </div>
                        </div>
                        @if($profile->principal_message)
                        <div class="flex-1 min-w-0">
                            <div class="border-l-4 border-rose-500 pl-5 mb-6"><p class="text-xs font-bold uppercase tracking-widest text-rose-600">Open Letter</p></div>
                            <div class="space-y-4 text-slate-700 leading-relaxed text-sm">
                                @foreach(array_filter(explode("\n\n", $profile->principal_message)) as $i => $para)
                                    <p class="{{ $i === 0 ? 'font-medium text-slate-800' : '' }}">{{ $para }}</p>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Leadership Team --}}
        @if($leadership->isNotEmpty())
        <section class="py-16 sm:py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-xs font-bold uppercase tracking-widest text-sky-600 mb-2">The People Behind the School</p>
                    <h2 class="text-3xl font-black text-slate-900">Leadership Team</h2>
                    <div class="mt-3 mx-auto w-10 h-1 rounded-full bg-rose-500"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($leadership as $i => $m)
                        <div class="rounded-2xl border {{ $i === 0 ? 'border-rose-200 hover:border-rose-400' : 'border-slate-200 hover:border-slate-300' }} bg-white p-6 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 flex flex-col items-center text-center">
                            <div class="relative mb-4">
                                <div class="w-24 h-24 rounded-full overflow-hidden ring-4 {{ $i === 0 ? 'ring-rose-100' : 'ring-slate-100' }} bg-slate-200">
                                    @if($m->photo_url)
                                        <img src="{{ $m->photo_url }}" alt="{{ $m->name }}" class="w-full h-full object-cover object-top">
                                    @endif
                                </div>
                            </div>
                            <p class="font-bold text-slate-900">{{ $m->name }}</p>
                            <p class="text-xs font-semibold uppercase tracking-wide mt-0.5 mb-3 {{ $i === 0 ? 'text-rose-600' : 'text-sky-600' }}">{{ $m->role }}</p>
                            @if($m->bio)
                                <p class="text-sm text-slate-600 leading-relaxed line-clamp-3">{{ $m->bio }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

    @endif

    {{-- History Tab --}}
    @if($activeTab === 'history')

        @if($history->isNotEmpty())
        <section class="py-16 sm:py-24 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-14 pb-10 border-b border-slate-100">
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-3">School History</p>
                    <h2 class="text-4xl sm:text-5xl font-black text-slate-900 mb-5">Our Journey</h2>
                </div>
                <div class="relative">
                    <div class="absolute left-5 top-0 bottom-0 w-px bg-gradient-to-b from-rose-300 via-slate-200 to-slate-100"></div>
                    <div class="space-y-10">
                        @foreach($history as $section)
                            <div class="relative flex gap-6 sm:gap-10">
                                <div class="flex-shrink-0 pt-0.5 z-10">
                                    <div class="w-10 h-10 rounded-full bg-white ring-4 ring-slate-200 flex items-center justify-center shadow-sm">
                                        <div class="w-3.5 h-3.5 rounded-full bg-rose-500"></div>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0 pb-4">
                                    @if($section->year_label)
                                        <span class="inline-block text-[11px] font-black uppercase tracking-wider border px-2.5 py-0.5 rounded-full mb-3 bg-rose-50 border-rose-200 text-rose-700">{{ $section->year_label }}</span>
                                    @endif
                                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 leading-snug">{{ $section->title }}</h3>
                                    <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 sm:p-7 space-y-3">
                                        @foreach(array_filter(explode("\n\n", $section->body)) as $para)
                                            <p class="text-slate-600 leading-relaxed">{{ $para }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Founding Teachers --}}
        @if($founders->isNotEmpty())
        <section class="py-16 sm:py-20 bg-stone-950 relative overflow-hidden">
            <div class="absolute inset-0 opacity-[0.03]" style="background-image:repeating-linear-gradient(0deg,#d97706 0,#d97706 1px,transparent 0,transparent 32px),repeating-linear-gradient(90deg,#d97706 0,#d97706 1px,transparent 0,transparent 32px)"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <h2 class="text-3xl font-black text-white">Our Founding Teachers</h2>
                    <p class="mt-3 text-amber-200/50 text-sm max-w-md mx-auto">The educators who built this school from the ground up.</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5">
                    @foreach($founders as $f)
                        <div class="group">
                            <div class="relative aspect-[3/4] overflow-hidden rounded-lg mb-3 ring-1 ring-amber-700/20 bg-stone-800">
                                @if($f->photo_url)
                                    <img src="{{ $f->photo_url }}" alt="{{ $f->name }}" class="w-full h-full object-cover object-top grayscale sepia brightness-75 group-hover:grayscale-0 group-hover:sepia-0 group-hover:brightness-90 transition-all duration-700">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/20 to-transparent"></div>
                                <div class="absolute top-2 left-2"><span class="bg-amber-500 text-stone-950 text-[9px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded-sm">Founder</span></div>
                            </div>
                            <p class="font-bold text-white text-sm leading-tight">{{ $f->name }}</p>
                            @if($f->subject)
                                <p class="text-amber-400 text-[11px] font-semibold uppercase tracking-wide mt-0.5">{{ $f->subject }}</p>
                            @endif
                            @if($f->tribute)
                                <p class="text-stone-400 text-xs mt-1.5 leading-relaxed line-clamp-3">{{ $f->tribute }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

    @endif

    {{-- Achievements Tab --}}
    @if($activeTab === 'achievements')
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="mb-10">
                <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">Recognition &amp; Honours</p>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900">Achievements</h2>
            </div>

            {{-- Filters --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-8">
                <div class="flex gap-2 flex-wrap">
                    @foreach(['all' => 'All', 'students' => 'Students', 'staff' => 'Staff', 'school' => 'School'] as $cat => $label)
                        <button wire:click="$set('achievementCategory', '{{ $cat }}')"
                                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                                       {{ $achievementCategory === $cat
                                           ? ($cat === 'students' ? 'bg-sky-600 border-sky-600 text-white' : ($cat === 'staff' ? 'bg-violet-600 border-violet-600 text-white' : ($cat === 'school' ? 'bg-rose-600 border-rose-600 text-white' : 'bg-slate-900 border-slate-900 text-white')))
                                           : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                @if($achievementYears->isNotEmpty())
                <select wire:model.live="achievementYear"
                        class="sm:ml-auto px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-rose-400">
                    <option value="all">All Years</option>
                    @foreach($achievementYears as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
                @endif
            </div>

            {{-- Grid --}}
            @if($achievements->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <p class="text-slate-500 font-medium">No achievements match this filter.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($achievements as $a)
                        <div class="bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-200 p-6 flex flex-col gap-4">
                            <div class="flex items-start gap-4">
                                @if($a->photo_url)
                                    <div class="w-20 h-20 rounded-full overflow-hidden ring-4 ring-slate-100 flex-shrink-0 bg-slate-200">
                                        <img src="{{ $a->photo_url }}" alt="{{ $a->person_name }}" class="w-full h-full object-cover object-top">
                                    </div>
                                @else
                                    <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-9 h-9 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0 pt-1">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <span class="text-[11px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full
                                            {{ $a->category === 'students' ? 'bg-sky-100 text-sky-700' : ($a->category === 'staff' ? 'bg-violet-100 text-violet-700' : 'bg-rose-100 text-rose-700') }}">
                                            {{ $a->category }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400 flex-shrink-0">{{ $a->year }}</span>
                                    </div>
                                    @if($a->person_name)
                                        <p class="text-sm font-semibold text-slate-700 mt-1">{{ $a->person_name }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-900 text-base leading-snug mb-2">{{ $a->title }}</h3>
                                @if($a->description)
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ $a->description }}</p>
                                @endif
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                                @if($a->award)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-black text-slate-900">
                                        <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        {{ $a->award }}
                                    </span>
                                @endif
                                @if($a->event_name)
                                    @if($a->award)<span class="text-slate-300">·</span>@endif
                                    <span class="text-xs text-slate-500">{{ $a->event_name }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

</div>
```

- [ ] **Step 3: Wire up Livewire model property to auto-refresh achievements grid**

In `About.php`, add `#[Url]` attributes if URL persistence is needed. For now the `wire:model.live` on the select and `wire:click` on filter buttons handle reactivity via Livewire automatically. No additional step needed — Livewire re-renders `render()` on any public property change.

- [ ] **Step 4: Visit `/about` in browser. Verify:**
  - All 3 tabs render without error
  - Achievements filter buttons and year select filter the grid reactively
  - Empty states show when no data is seeded
  - Page renders correctly on mobile

- [ ] **Step 5: Commit**

```bash
git add app/Livewire/Website/About.php resources/views/livewire/website/about.blade.php
git commit -m "feat: add public About page with tab navigation and achievement filters"
```

---

## Self-Review

**Spec coverage check:**
- ✅ Mission & Vision CMS (Task 3) + website rendering (Task 8, About tab)
- ✅ Leadership Team CMS (Task 4) + website rendering (Task 8, About tab)
- ✅ Founding Members CMS (Task 5) + website rendering (Task 8, History tab)
- ✅ History Sections CMS (Task 6) + website rendering (Task 8, History tab)
- ✅ Achievements CMS (Task 7) + website rendering with filters (Task 8, Achievements tab)
- ✅ CMS tab nav partial (Task 2)
- ✅ Sidebar About entry (Task 2)
- ✅ Routes: 5 CMS + 1 website (Task 2)
- ✅ All migrations and models (Task 1)
- ✅ Empty states on website (handled with `@if` guards throughout)
- ✅ Achievement filter: category + year via Livewire state

**Placeholder scan:** No TBDs, no "implement later", all blade templates fully written, all PHP files complete.

**Type consistency:**
- `Achievement::resolvePhotoUrl()` — defined in model (Task 1 Step 11), called in view (Task 7 Step 2) ✅
- `LeadershipMember::resolvePhotoUrl()` — defined in model (Task 1 Step 8), called in view (Task 4 Step 2) ✅
- `FoundingMember::resolvePhotoUrl()` — defined in model (Task 1 Step 9), called in view (Task 5 Step 2) ✅
- `Mission::singleton()` — defined in model (Task 1 Step 7), called in MissionEdit (Task 3 Step 1) and About (Task 8 Step 1) ✅
- `$achievementCategory`, `$achievementYear` — defined in About.php (Task 8 Step 1), referenced in blade (Task 8 Step 2) ✅
- `$achievementYears` — passed from `render()` (Task 8 Step 1), used in blade select (Task 8 Step 2) ✅
