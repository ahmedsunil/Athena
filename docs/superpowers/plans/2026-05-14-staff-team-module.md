# Staff / Team Module Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a Staff/Team module with a CMS page (About → Team) and a public Team tab on the About page, displaying the school hierarchy as collapsible sections with inline-expanding staff cards.

**Architecture:** Migration + `StaffMember` model → `StaffIndex` Livewire CMS component → `staff-index` blade view → extend `About` Livewire component + `about.blade.php` with a Team tab. All patterns follow the existing `AchievementsIndex` / `LeadershipMember` conventions.

**Tech Stack:** Laravel 11, Livewire 4, Alpine.js, Tailwind CSS (CDN), `WithFileUploads`, MySQL JSON column.

---

### Task 1: Migration + Model

**Files:**
- Create: `database/migrations/2026_05_14_200000_create_staff_members_table.php`
- Create: `app/Models/StaffMember.php`

- [ ] **Step 1: Create the migration**

```php
<?php
// database/migrations/2026_05_14_200000_create_staff_members_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation');
            $table->string('education')->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('section', ['senior_management', 'academic', 'administrative']);
            $table->string('sub_section', 50)->nullable();
            $table->json('work_experiences')->default('[]');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_members');
    }
};
```

- [ ] **Step 2: Run the migration**

```bash
php artisan migrate
```

Expected: `Migrating: 2026_05_14_200000_create_staff_members_table` then `Migrated`.

- [ ] **Step 3: Create the model**

```php
<?php
// app/Models/StaffMember.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StaffMember extends Model
{
    protected $fillable = [
        'name', 'designation', 'education', 'photo_path',
        'section', 'sub_section', 'work_experiences',
        'sort_order', 'is_active',
    ];

    protected $casts = [
        'work_experiences' => 'array',
        'is_active'        => 'boolean',
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

- [ ] **Step 4: Verify model works**

```bash
php artisan tinker --execute="echo App\Models\StaffMember::count();"
```

Expected: `0`

- [ ] **Step 5: Commit**

```bash
git add database/migrations/2026_05_14_200000_create_staff_members_table.php app/Models/StaffMember.php
git commit -m "feat: add staff_members migration and StaffMember model"
```

---

### Task 2: CMS Livewire Component

**Files:**
- Create: `app/Livewire/Cms/About/StaffIndex.php`

- [ ] **Step 1: Create the component**

```php
<?php
// app/Livewire/Cms/About/StaffIndex.php

namespace App\Livewire\Cms\About;

use App\Models\StaffMember;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class StaffIndex extends Component
{
    use WithFileUploads;

    public string  $name        = '';
    public string  $designation = '';
    public string  $education   = '';
    public string  $section     = 'academic';
    public string  $subSection  = '';
    public int     $sortOrder   = 0;
    public bool    $isActive    = true;
    public         $photo       = null;
    public ?string $existingPhotoPath = null;
    public bool    $photoRemoved = false;
    public ?int    $editingId   = null;
    public array   $workExperiences = [];

    protected function rules(): array
    {
        return [
            'name'                          => ['required', 'string', 'max:255'],
            'designation'                   => ['required', 'string', 'max:255'],
            'education'                     => ['nullable', 'string', 'max:255'],
            'section'                       => ['required', 'in:senior_management,academic,administrative'],
            'subSection'                    => ['nullable', 'string', 'max:50'],
            'sortOrder'                     => ['integer', 'min:0'],
            'isActive'                      => ['boolean'],
            'photo'                         => ['nullable', 'image', 'max:2048'],
            'workExperiences'               => ['array'],
            'workExperiences.*.title'       => ['required', 'string', 'max:255'],
            'workExperiences.*.institution' => ['required', 'string', 'max:255'],
            'workExperiences.*.period'      => ['required', 'string', 'max:100'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'             => $this->name,
            'designation'      => $this->designation,
            'education'        => $this->education ?: null,
            'section'          => $this->section,
            'sub_section'      => $this->section === 'senior_management' ? null : ($this->subSection ?: null),
            'sort_order'       => $this->sortOrder,
            'is_active'        => $this->isActive,
            'work_experiences' => array_values($this->workExperiences),
        ];

        if ($this->photo) {
            if ($this->existingPhotoPath) {
                Storage::disk('public')->delete($this->existingPhotoPath);
            }
            $data['photo_path'] = $this->photo->store('staff-photos', 'public');
        } elseif ($this->photoRemoved) {
            if ($this->existingPhotoPath) {
                Storage::disk('public')->delete($this->existingPhotoPath);
            }
            $data['photo_path'] = null;
        }

        if ($this->editingId) {
            StaffMember::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Staff member updated.');
        } else {
            StaffMember::create($data);
            $this->dispatch('toast', message: 'Staff member added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = StaffMember::findOrFail($id);
        $this->editingId         = $item->id;
        $this->name              = $item->name;
        $this->designation       = $item->designation;
        $this->education         = $item->education ?? '';
        $this->section           = $item->section;
        $this->subSection        = $item->sub_section ?? '';
        $this->sortOrder         = $item->sort_order;
        $this->isActive          = $item->is_active;
        $this->existingPhotoPath = $item->photo_path;
        $this->photoRemoved      = false;
        $this->photo             = null;
        $this->workExperiences   = $item->work_experiences ?? [];
    }

    public function delete(int $id): void
    {
        $item = StaffMember::findOrFail($id);
        if ($item->photo_path) {
            Storage::disk('public')->delete($item->photo_path);
        }
        $item->delete();
        $this->dispatch('toast', message: 'Staff member deleted.');
    }

    public function removePhoto(): void
    {
        $this->photo             = null;
        $this->existingPhotoPath = null;
        $this->photoRemoved      = true;
    }

    public function addExperience(): void
    {
        $this->workExperiences[] = ['title' => '', 'institution' => '', 'period' => ''];
    }

    public function removeExperience(int $index): void
    {
        array_splice($this->workExperiences, $index, 1);
        $this->workExperiences = array_values($this->workExperiences);
    }

    public function updatedSection(): void
    {
        $this->subSection = '';
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset([
            'name', 'designation', 'education', 'subSection',
            'sortOrder', 'photo', 'existingPhotoPath', 'photoRemoved',
            'editingId', 'workExperiences',
        ]);
        $this->section   = 'academic';
        $this->isActive  = true;
        $this->sortOrder = 0;
    }

    public function render()
    {
        return view('livewire.cms.about.staff-index', [
            'staffMembers' => StaffMember::orderByRaw("FIELD(section,'senior_management','academic','administrative')")
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
        ])->layout('layouts.app', ['title' => 'Team']);
    }
}
```

- [ ] **Step 2: Verify no syntax errors**

```bash
php artisan route:list --name=cms.about 2>&1 | head -5
```

(Route doesn't exist yet — just checking PHP parses OK. Expected: table output or empty, no parse error.)

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Cms/About/StaffIndex.php
git commit -m "feat: add StaffIndex CMS Livewire component"
```

---

### Task 3: CMS Blade View

**Files:**
- Create: `resources/views/livewire/cms/about/staff-index.blade.php`

- [ ] **Step 1: Create the blade view**

```blade
{{-- resources/views/livewire/cms/about/staff-index.blade.php --}}
<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">Team</h1>
        <p class="admin-muted">Manage staff members displayed on the public About → Team tab.</p>
    </div>

    {{-- Form --}}
    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit staff member' : 'Add staff member' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Name + Designation --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="Full name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Designation <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="designation" placeholder="e.g. Head of Mathematics"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('designation') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Education --}}
            <div>
                <label class="mb-1.5 block admin-label">Education <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="education" placeholder="e.g. B.Sc Mathematics · M.Ed Curriculum"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('education') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Section + Sub-section --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Section <span class="text-red-500">*</span></label>
                    <select wire:model.live="section"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="senior_management">Senior Management</option>
                        <option value="academic">Academic</option>
                        <option value="administrative">Administrative</option>
                    </select>
                    @error('section') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                @if($section !== 'senior_management')
                <div>
                    <label class="mb-1.5 block admin-label">Sub-section</label>
                    <select wire:model="subSection"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="">— None —</option>
                        @if($section === 'academic')
                            <option value="leading_teachers">Leading Teachers</option>
                            <option value="teachers">Teachers</option>
                            <option value="academic_support">Academic Support Staff</option>
                            <option value="laboratory">Laboratory (under Academic Support)</option>
                            <option value="library">Library (under Academic Support)</option>
                        @else
                            <option value="hr">HR</option>
                            <option value="budget">Budget</option>
                            <option value="it">IT</option>
                            <option value="printer">Printer</option>
                        @endif
                    </select>
                    @error('subSection') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                @endif
            </div>

            {{-- Sort order + Status --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sortOrder" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('isActive', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $isActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('isActive', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$isActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>

            {{-- Photo upload --}}
            <div>
                <label class="mb-1.5 block admin-label">Photo <span class="text-zinc-400">(optional)</span></label>
                @if($photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-12 w-12 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existingPhotoPath)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\StaffMember::resolvePhotoUrl($existingPhotoPath) }}" alt="" class="h-12 w-12 rounded-full object-cover">
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

            {{-- Work Experiences --}}
            <div>
                <p class="admin-label mb-2 border-t border-zinc-100 pt-4">Work Experience <span class="text-zinc-400 font-normal">(optional)</span></p>
                <div class="space-y-2">
                    @foreach($workExperiences as $i => $exp)
                        <div class="grid gap-2 sm:grid-cols-3 items-start">
                            <div>
                                <input type="text" wire:model="workExperiences.{{ $i }}.title" placeholder="Job Title"
                                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                @error("workExperiences.{$i}.title") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <input type="text" wire:model="workExperiences.{{ $i }}.institution" placeholder="Institution"
                                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                @error("workExperiences.{$i}.institution") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    <input type="text" wire:model="workExperiences.{{ $i }}.period" placeholder="e.g. 2018 – Present"
                                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                    @error("workExperiences.{$i}.period") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                                </div>
                                <button type="button" wire:click="removeExperience({{ $i }})"
                                        class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-md border border-zinc-200 text-zinc-400 hover:border-red-300 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addExperience"
                            class="text-xs font-medium text-zinc-500 hover:text-zinc-950 flex items-center gap-1 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        Add experience
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add staff member' }}
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

    {{-- Staff list table --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Designation</th>
            <th class="admin-table-heading">Section</th>
            <th class="admin-table-heading">Sub-section</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($staffMembers as $s)
                <tr>
                    <td class="admin-table-cell">
                        @if($s->photo_url)
                            <img src="{{ $s->photo_url }}" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="h-8 w-8 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-500 text-xs font-bold">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $s->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $s->designation }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $s->section === 'senior_management' ? 'bg-rose-100 text-rose-700' : ($s->section === 'academic' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ match($s->section) { 'senior_management' => 'Senior Mgmt', 'academic' => 'Academic', 'administrative' => 'Admin' } }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400 text-xs">{{ $s->sub_section ? str_replace('_', ' ', ucfirst($s->sub_section)) : '—' }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $s->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $s->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $s->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete {{ $s->name }}?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No staff members yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($staffMembers as $s)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($s->photo_url)
                        <img src="{{ $s->photo_url }}" class="h-8 w-8 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="h-8 w-8 rounded-full bg-zinc-200 flex items-center justify-center text-zinc-500 text-xs font-bold flex-shrink-0">{{ strtoupper(substr($s->name, 0, 1)) }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $s->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $s->designation }} · {{ str_replace('_', ' ', $s->section) }}</p>
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

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/cms/about/staff-index.blade.php
git commit -m "feat: add StaffIndex CMS blade view"
```

---

### Task 4: Wire Up Route + CMS Tab

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/partials/cms-about-tabs.blade.php`

- [ ] **Step 1: Add import + route to `routes/web.php`**

After the existing `use App\Livewire\Cms\About\AchievementsIndex;` line, add:

```php
use App\Livewire\Cms\About\StaffIndex;
```

After the existing achievements route:

```php
Route::get('/cms/about/achievements', AchievementsIndex::class)->name('cms.about.achievements');
```

Add:

```php
Route::get('/cms/about/team', StaffIndex::class)->name('cms.about.team');
```

- [ ] **Step 2: Add tab to `cms-about-tabs.blade.php`**

In `resources/views/layouts/partials/cms-about-tabs.blade.php`, inside the `$aboutTabs` array after the Achievements entry:

```php
['label' => 'Team',            'route' => 'cms.about.team',               'href' => route('cms.about.team')],
```

- [ ] **Step 3: Verify route loads**

```bash
php artisan route:list --name=cms.about.team
```

Expected: one row showing `GET /cms/about/team`.

- [ ] **Step 4: Commit**

```bash
git add routes/web.php resources/views/layouts/partials/cms-about-tabs.blade.php
git commit -m "feat: register cms.about.team route and tab"
```

---

### Task 5: Website — Team Tab (About component + blade)

**Files:**
- Modify: `app/Livewire/Website/About.php`
- Modify: `resources/views/livewire/website/about.blade.php`

- [ ] **Step 1: Add StaffMember import + loading to `About.php`**

At the top of `app/Livewire/Website/About.php`, add after the existing `use` statements:

```php
use App\Models\StaffMember;
```

In the `render()` method, before the `return view(...)`, add:

```php
$staff = StaffMember::where('is_active', true)
    ->orderBy('sort_order')
    ->orderBy('id')
    ->get()
    ->groupBy('section');
```

Add `'staff' => $staff` to the array passed to `view()`.

- [ ] **Step 2: Add Team tab button to `about.blade.php`**

In `resources/views/livewire/website/about.blade.php`, in the tab nav `<div class="flex gap-0 overflow-x-auto">` section, after the Achievements button:

```blade
<button
    wire:click="switchTab('team')"
    class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors {{ $activeTab === 'team' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
>Team</button>
```

- [ ] **Step 3: Add Team tab content to `about.blade.php`**

After the closing `@endif` of the Achievements tab block, add:

```blade
{{-- ===================== TAB: TEAM ===================== --}}
@if($activeTab === 'team')
<div class="py-12 sm:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- ── Senior Management ── --}}
        @php $seniorStaff = $staff->get('senior_management', collect()); @endphp
        <div class="bg-white rounded-2xl border border-rose-200 overflow-hidden" data-reveal="scale">
            <div class="px-6 py-4 border-b border-rose-100 bg-rose-50">
                <p class="text-xs font-bold uppercase tracking-widest text-rose-600">Leadership</p>
                <h2 class="text-xl font-black text-slate-900 mt-0.5">Senior Management Team</h2>
            </div>
            <div class="p-6">
                @if($seniorStaff->isEmpty())
                    <p class="text-sm text-slate-400">No members added yet.</p>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($seniorStaff as $member)
                            @include('livewire.website.partials.staff-card', ['member' => $member, 'accentColor' => 'rose'])
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ── Academic Section ── --}}
        @php
            $academicStaff = $staff->get('academic', collect())->groupBy('sub_section');
            $academicSubSections = [
                'leading_teachers' => 'Leading Teachers',
                'teachers'         => 'Teachers',
                'academic_support' => 'Academic Support Staff',
                'laboratory'       => 'Laboratory',
                'library'          => 'Library',
            ];
        @endphp
        <div x-data="{ open: false }" class="bg-white rounded-2xl border border-sky-200 overflow-hidden" data-reveal="scale" style="--reveal-delay: 80ms">
            <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 border-b border-sky-100 bg-sky-50 hover:bg-sky-100 transition-colors">
                <div class="text-left">
                    <p class="text-xs font-bold uppercase tracking-widest text-sky-600">Section</p>
                    <h2 class="text-xl font-black text-slate-900 mt-0.5">Academic Section</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Principal as Team Lead</p>
                </div>
                <svg class="w-5 h-5 text-sky-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 space-y-8">
                @forelse($academicSubSections as $key => $label)
                    @php $group = $academicStaff->get($key, collect()); @endphp
                    @if($group->isNotEmpty())
                        @php $isNested = in_array($key, ['laboratory', 'library']); @endphp
                        <div class="{{ $isNested ? 'ml-6 pl-4 border-l-2 border-sky-100' : '' }}">
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-500 mb-4">{{ $label }}</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($group as $member)
                                    @include('livewire.website.partials.staff-card', ['member' => $member, 'accentColor' => 'sky'])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-slate-400">No academic staff added yet.</p>
                @endforelse
            </div>
        </div>

        {{-- ── Administrative Section ── --}}
        @php
            $adminStaff = $staff->get('administrative', collect())->groupBy('sub_section');
            $adminSubSections = [
                'hr'      => 'Human Resources',
                'budget'  => 'Budget',
                'it'      => 'IT',
                'printer' => 'Printing',
            ];
        @endphp
        <div x-data="{ open: false }" class="bg-white rounded-2xl border border-amber-200 overflow-hidden" data-reveal="scale" style="--reveal-delay: 160ms">
            <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 border-b border-amber-100 bg-amber-50 hover:bg-amber-100 transition-colors">
                <div class="text-left">
                    <p class="text-xs font-bold uppercase tracking-widest text-amber-600">Section</p>
                    <h2 class="text-xl font-black text-slate-900 mt-0.5">Administrative Section</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Administrator as Team Lead</p>
                </div>
                <svg class="w-5 h-5 text-amber-400 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 space-y-8">
                @forelse($adminSubSections as $key => $label)
                    @php $group = $adminStaff->get($key, collect()); @endphp
                    @if($group->isNotEmpty())
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-amber-500 mb-4">{{ $label }}</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($group as $member)
                                    @include('livewire.website.partials.staff-card', ['member' => $member, 'accentColor' => 'amber'])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-sm text-slate-400">No administrative staff added yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endif
```

- [ ] **Step 4: Commit**

```bash
git add app/Livewire/Website/About.php resources/views/livewire/website/about.blade.php
git commit -m "feat: add Team tab to website About page"
```

---

### Task 6: Staff Card Partial

**Files:**
- Create: `resources/views/livewire/website/partials/staff-card.blade.php`

- [ ] **Step 1: Create the partial**

```blade
{{-- resources/views/livewire/website/partials/staff-card.blade.php --}}
{{-- Variables: $member (StaffMember), $accentColor ('rose'|'sky'|'amber') --}}
@php
    $colorMap = [
        'rose'  => ['ring' => 'ring-rose-100',  'border' => 'border-rose-300',  'text' => 'text-rose-600',  'bg' => 'bg-rose-50'],
        'sky'   => ['ring' => 'ring-sky-100',   'border' => 'border-sky-300',   'text' => 'text-sky-600',   'bg' => 'bg-sky-50'],
        'amber' => ['ring' => 'ring-amber-100', 'border' => 'border-amber-300', 'text' => 'text-amber-600', 'bg' => 'bg-amber-50'],
    ];
    $c = $colorMap[$accentColor] ?? $colorMap['sky'];
@endphp

<div x-data="{ expanded: false }">
    {{-- Card --}}
    <div
        @click="expanded = !expanded"
        class="bg-white border border-slate-200 rounded-2xl p-4 cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 select-none"
        :class="expanded ? 'rounded-b-none border-b-0 shadow-md' : ''"
    >
        {{-- Avatar --}}
        <div class="mb-3">
            <div class="w-12 h-12 rounded-full overflow-hidden ring-4 {{ $c['ring'] }}">
                @if($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover object-top">
                @else
                    <div class="w-full h-full {{ $c['bg'] }} flex items-center justify-center {{ $c['text'] }} text-lg font-black">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>
        {{-- Info --}}
        <p class="font-bold text-slate-900 text-sm leading-snug">{{ $member->name }}</p>
        <p class="text-xs {{ $c['text'] }} font-semibold mt-0.5">{{ $member->designation }}</p>
        {{-- Expand indicator --}}
        <div class="mt-2 flex items-center gap-1 text-[10px] font-semibold text-slate-400" x-show="!expanded">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            View details
        </div>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-semibold {{ $c['text'] }}" x-show="expanded">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
            Close
        </div>
    </div>

    {{-- Inline detail panel --}}
    <div
        x-show="expanded"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="bg-slate-50 border border-slate-200 border-t-0 rounded-b-2xl px-4 pb-4 pt-3"
    >
        @if($member->education)
            <p class="text-xs text-slate-500 mb-3">
                <span class="font-semibold text-slate-700">Education:</span> {{ $member->education }}
            </p>
        @endif

        @if(!empty($member->work_experiences))
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Work Experience</p>
            <div class="space-y-2">
                @foreach($member->work_experiences as $exp)
                    <div class="border-l-2 {{ $c['border'] }} pl-3">
                        <p class="text-xs font-semibold text-slate-800">{{ $exp['title'] }}</p>
                        <p class="text-xs text-slate-500">{{ $exp['institution'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $exp['period'] }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 italic">No experience details added.</p>
        @endif
    </div>
</div>
```

- [ ] **Step 2: Ensure partials directory exists**

```bash
mkdir -p /Users/shaan/Herd/Athena/resources/views/livewire/website/partials
```

- [ ] **Step 3: Verify the about page loads without errors**

```bash
php artisan route:list --name=about
```

Open `http://athena.test/about` in browser, click the Team tab — should render the three section blocks (empty state messages if no data).

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/website/partials/staff-card.blade.php
git commit -m "feat: add staff-card partial for Team tab"
```

---

## Self-Review

**Spec coverage:**
- ✅ `staff_members` table with all specified columns
- ✅ `StaffMember` model with `work_experiences` array cast + photo URL accessor
- ✅ CMS route `cms.about.team` + tab in `cms-about-tabs.blade.php`
- ✅ CMS form: name, designation, education, section, sub_section (filtered by section), sort order, active toggle, photo upload, dynamic work experience rows
- ✅ Photo deletion on record delete + photo replacement on update
- ✅ `updatedSection()` clears sub_section
- ✅ Website: Team tab button added
- ✅ Senior Management — always open, rose accent
- ✅ Academic Section — collapsible, sky accent, sub-sections in correct order, laboratory/library nested (visually indented with left border)
- ✅ Administrative Section — collapsible, amber accent, sub-sections hr/budget/it/printer
- ✅ Staff card: avatar with initials fallback, name, designation, click toggles inline detail
- ✅ Inline detail: education + work experience list with left accent border
- ✅ `data-reveal="scale"` on section blocks with stagger delays

**Placeholder scan:** None found. All code blocks are complete.

**Type consistency:** `StaffMember` model, `$member` in partial, `->photo_url` accessor — all consistent across Tasks 1, 2, 3, 5, 6. `$staff->get('section_key', collect())` used consistently in blade.
