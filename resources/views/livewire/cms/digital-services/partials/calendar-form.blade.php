<div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
    <div class="mb-4">
        <h3 class="admin-section-title">{{ $calendarEditingId ? 'Edit calendar' : 'New calendar' }}</h3>
    </div>
    <form wire:submit="saveCalendar" class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block admin-label">Title <span class="text-red-500">*</span></label>
                <input type="text" wire:model="calendarTitle" placeholder="e.g. Academic Calendar 2026"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('calendarTitle') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Year <span class="text-red-500">*</span></label>
                <input type="number" wire:model="calendarYear" min="2000" max="2100"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('calendarYear') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="mb-1.5 block admin-label">Description <span class="text-zinc-400">(optional)</span></label>
            <textarea wire:model="calendarDescription" rows="2"
                      class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
            @error('calendarDescription') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block admin-label">Sort order</label>
                <input type="number" wire:model="calendarSortOrder" min="0"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('calendarSortOrder') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Active calendar</label>
                <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                    <button type="button" wire:click="$set('calendarIsActive', true)"
                            class="rounded-[5px] admin-link-label transition-colors {{ $calendarIsActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Yes</button>
                    <button type="button" wire:click="$set('calendarIsActive', false)"
                            class="rounded-[5px] admin-link-label transition-colors {{ !$calendarIsActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">No</button>
                </div>
                <p class="mt-1 text-xs text-zinc-400">Only one calendar can be active at a time.</p>
            </div>
        </div>

        <div>
            <p class="admin-label mb-3 border-t border-zinc-100 pt-4">Official Stats <span class="text-zinc-400 font-normal">(from MoE calendar — optional)</span></p>
            <div class="grid gap-3 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Teaching Days</label>
                    <input type="number" step="0.5" wire:model="statTeachingDays" placeholder="e.g. 84.5"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('statTeachingDays') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">School Exams</label>
                    <input type="number" step="0.5" wire:model="statExamDays" placeholder="e.g. 8"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('statExamDays') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Report Preparation</label>
                    <input type="number" step="0.5" wire:model="statReportPrepDays" placeholder="e.g. 5"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('statReportPrepDays') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Teacher PD</label>
                    <input type="number" step="0.5" wire:model="statTeacherPdDays" placeholder="e.g. 6"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('statTeacherPdDays') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Non-Teaching</label>
                    <input type="number" step="0.5" wire:model="statNonTeachingDays" placeholder="e.g. 3.5"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('statNonTeachingDays') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Total School Days</label>
                    <input type="number" step="0.5" wire:model="statTotalDays" placeholder="e.g. 107"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('statTotalDays') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                {{ $calendarEditingId ? 'Update calendar' : 'Add calendar' }}
            </button>
            <button type="button" wire:click="cancelCalendar"
                    class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                Cancel
            </button>
        </div>
    </form>
</div>
