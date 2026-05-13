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
