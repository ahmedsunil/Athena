<div class="space-y-4">

    @include('layouts.partials.cms-academics-tabs')

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
