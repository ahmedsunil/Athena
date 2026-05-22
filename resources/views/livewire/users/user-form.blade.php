<div class="mx-auto max-w-4xl space-y-4">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <div>
            <h1 class="admin-page-title">
                {{ $userId ? 'Edit user' : 'New user' }}
            </h1>
            <p class="admin-caption">Manage access, credentials, role, and account status.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-4">

        {{-- Basic info --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Basic information</h3>
                <p class="admin-caption">Primary identity details for this user.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Full name</label>
                    <input type="text" wire:model="name" autocomplete="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block admin-label">Email address</label>
                    <input type="email" wire:model="email" autocomplete="email"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('email') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Password</h3>
                @if($userId)
                    <p class="admin-caption">Leave blank to keep the current password.</p>
                @else
                    <p class="admin-caption">Must be at least 8 characters.</p>
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">
                        {{ $userId ? 'New password' : 'Password' }}
                    </label>
                    <input type="password" wire:model="password" autocomplete="new-password"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('password') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block admin-label">Confirm password</label>
                    <input type="password" wire:model="password_confirmation" autocomplete="new-password"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('password_confirmation') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Role & Status --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Role & status</h3>
                <p class="admin-caption">Assign permissions and enable or disable access.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Roles</label>
                    <div class="rounded-md border border-zinc-200 bg-white p-2 shadow-sm">
                        <div class="mb-2 flex items-center gap-2 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1.5">
                            <svg class="h-3.5 w-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/>
                            </svg>
                            <input wire:model.live.debounce.250ms="roleSearch"
                                   type="text"
                                   placeholder="Search roles..."
                                   class="w-full bg-transparent text-xs font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:outline-none">
                        </div>

                        <div class="grid gap-1 sm:grid-cols-2">
                            @forelse($roles as $role)
                                <label class="flex cursor-pointer items-center gap-2 rounded-md px-2 py-1.5 text-sm text-zinc-700 hover:bg-zinc-50">
                                    <input type="checkbox" wire:model.live="selectedRoles" value="{{ $role }}"
                                           class="h-3.5 w-3.5 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                                    <span>{{ ucfirst($role) }}</span>
                                </label>
                            @empty
                                <p class="px-2 py-1.5 admin-muted">No roles match.</p>
                            @endforelse
                        </div>

                        @if(count($selectedRoles) > 0)
                            <div class="mt-2 border-t border-zinc-100 pt-2">
                                <button type="button" wire:click="clearRoles" class="admin-muted hover:text-zinc-700">
                                    Clear selection
                                </button>
                            </div>
                        @endif
                        </div>
                    @error('selectedRoles') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    @error('selectedRoles.*') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button"
                                wire:click="$set('isActive', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $isActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                            Active
                        </button>
                        <button type="button"
                                wire:click="$set('isActive', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ ! $isActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                            Inactive
                        </button>
                    </div>
                    @error('isActive') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('users.index') }}"
               class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:ring-offset-2">
                {{ $userId ? 'Update user' : 'Create user' }}
            </button>
        </div>

    </form>

</div>
