<div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
    <div class="mb-4">
        <h2 class="text-sm font-semibold text-stone-900">Update Password</h2>
        <p class="mt-0.5 text-xs text-stone-400">Use a long, random password for security.</p>
    </div>

    <form wire:submit="updatePassword">
        <div class="space-y-4">
            <div>
                <label for="currentPassword" class="mb-1.5 block text-xs font-medium text-stone-700">Current Password</label>
                <input
                    id="currentPassword"
                    type="password"
                    wire:model="currentPassword"
                    autocomplete="current-password"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                    placeholder="••••••••"
                >
                @error('currentPassword')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-xs font-medium text-stone-700">New Password</label>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="passwordConfirmation" class="mb-1.5 block text-xs font-medium text-stone-700">Confirm Password</label>
                <input
                    id="passwordConfirmation"
                    type="password"
                    wire:model="passwordConfirmation"
                    autocomplete="new-password"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                    placeholder="••••••••"
                >
                @error('passwordConfirmation')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div
            x-data="{ saved: @entangle('status') }"
            x-init="$watch('saved', val => { if (val === 'saved') { setTimeout(() => { $wire.set('status', '') }, 3000) } })"
            class="mt-4 flex items-center gap-3"
        >
            <button
                type="submit"
                class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700"
            >
                Save
            </button>

            <div x-show="saved === 'saved'" x-transition class="rounded-lg bg-teal-50 px-3 py-2 text-xs text-teal-700">
                Saved.
            </div>
        </div>
    </form>
</div>
