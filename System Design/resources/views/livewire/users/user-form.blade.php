<div class="mx-auto max-w-2xl space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="text-stone-400 hover:text-stone-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <h1 class="text-base font-semibold text-stone-900">
            {{ $userId ? 'Edit User' : 'New User' }}
        </h1>
    </div>

    <form wire:submit="save" class="space-y-6">

        {{-- Basic info --}}
        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-stone-900">Basic information</h3>

            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-stone-700">Full name</label>
                    <input type="text" wire:model="name" autocomplete="name"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-stone-700">Email address</label>
                    <input type="email" wire:model="email" autocomplete="email"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-1 text-sm font-semibold text-stone-900">Password</h3>
            @if($userId)
                <p class="mb-4 text-xs text-stone-400">Leave blank to keep the current password.</p>
            @else
                <p class="mb-4 text-xs text-stone-400">Must be at least 8 characters.</p>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-stone-700">
                        {{ $userId ? 'New password' : 'Password' }}
                    </label>
                    <input type="password" wire:model="password" autocomplete="new-password"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-stone-700">Confirm password</label>
                    <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                           class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    @error('passwordConfirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Role & Status --}}
        <div class="rounded-2xl border border-stone-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 text-sm font-semibold text-stone-900">Role & Status</h3>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-stone-700">Role</label>
                    <select wire:model="role"
                            class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-stone-700">Status</label>
                    <label class="flex cursor-pointer items-center gap-3 pt-2">
                        <div x-data="{ on: @entangle('isActive') }" @click="on = !on"
                             :class="on ? 'bg-teal-600' : 'bg-stone-300'"
                             class="relative h-5 w-9 rounded-full transition-colors">
                            <span :class="on ? 'translate-x-4' : 'translate-x-0.5'"
                                  class="absolute top-0.5 h-4 w-4 transform rounded-full bg-white shadow transition-transform"></span>
                        </div>
                        <span class="text-sm text-stone-700" x-data="{ on: @entangle('isActive') }" x-text="on ? 'Active' : 'Inactive'"></span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('users.index') }}"
               class="rounded-lg border border-stone-300 px-4 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-lg bg-teal-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-teal-700">
                {{ $userId ? 'Update user' : 'Create user' }}
            </button>
        </div>

    </form>

</div>
