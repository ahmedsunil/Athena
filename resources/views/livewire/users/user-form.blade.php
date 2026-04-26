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
            <h1 class="text-base font-semibold text-zinc-950">
                {{ $userId ? 'Edit user' : 'New user' }}
            </h1>
            <p class="text-xs text-zinc-500">Manage access, credentials, role, and account status.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-4">

        {{-- Basic info --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-zinc-950">Basic information</h3>
                <p class="text-xs text-zinc-500">Primary identity details for this user.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Full name</label>
                    <input type="text" wire:model="name" autocomplete="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Email address</label>
                    <input type="email" wire:model="email" autocomplete="email"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Password --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-zinc-950">Password</h3>
                @if($userId)
                    <p class="text-xs text-zinc-500">Leave blank to keep the current password.</p>
                @else
                    <p class="text-xs text-zinc-500">Must be at least 8 characters.</p>
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">
                        {{ $userId ? 'New password' : 'Password' }}
                    </label>
                    <input type="password" wire:model="password" autocomplete="new-password"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Confirm password</label>
                    <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('passwordConfirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Role & Status --}}
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-zinc-950">Role & status</h3>
                <p class="text-xs text-zinc-500">Assign permissions and enable or disable access.</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Roles</label>
                    <div x-data="{
                            open: false,
                            search: '',
                            allRoles: @js($roles->values()->all()),
                            get selected() { return $wire.selectedRoles ?? [] },
                            get filtered() {
                                if (!this.search) return this.allRoles;
                                return this.allRoles.filter(r => r.toLowerCase().includes(this.search.toLowerCase()));
                            },
                            toggle(role) {
                                const s = this.selected;
                                $wire.selectedRoles = s.includes(role) ? s.filter(r => r !== role) : [...s, role];
                            },
                            has(role) { return this.selected.includes(role) },
                            cap(str) { return str.charAt(0).toUpperCase() + str.slice(1) },
                            openDropdown() { this.open = true; this.$nextTick(() => this.$refs.search?.focus()); }
                         }"
                         @click.outside="open = false; search = ''"
                         @keydown.escape="open = false; search = ''"
                         class="relative">

                        {{-- Trigger --}}
                        <button type="button"
                                @click="openDropdown()"
                                class="flex min-h-9 w-full flex-wrap items-center gap-1.5 rounded-md border border-zinc-200 bg-white px-2.5 py-1.5 text-left shadow-sm transition-colors hover:border-zinc-300 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <template x-if="selected.length === 0">
                                <span class="text-sm text-zinc-400">Select roles…</span>
                            </template>
                            <template x-for="role in selected" :key="role">
                                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-700">
                                    <span x-text="cap(role)"></span>
                                    <button type="button"
                                            @click.stop="toggle(role)"
                                            class="rounded-full text-zinc-400 hover:text-zinc-700 focus:outline-none">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </span>
                            </template>
                            <svg class="ml-auto h-4 w-4 shrink-0 text-zinc-400 transition-transform"
                                 :class="open ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 -translate-y-1 scale-95"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-1 scale-95"
                             class="absolute z-20 mt-1 w-full rounded-md border border-zinc-200 bg-white shadow-lg">

                            {{-- Search --}}
                            <div class="border-b border-zinc-100 p-2">
                                <div class="flex items-center gap-2 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1.5">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/>
                                    </svg>
                                    <input x-ref="search"
                                           x-model="search"
                                           type="text"
                                           placeholder="Search roles…"
                                           class="w-full bg-transparent text-xs text-zinc-700 placeholder-zinc-400 focus:outline-none">
                                </div>
                            </div>

                            {{-- Options --}}
                            <div class="max-h-48 overflow-y-auto py-1">
                                <template x-for="role in filtered" :key="role">
                                    <button type="button"
                                            @click="toggle(role)"
                                            class="flex w-full items-center gap-2.5 px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
                                        <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-colors"
                                              :class="has(role) ? 'bg-zinc-950 border-zinc-950' : 'border-zinc-300'">
                                            <svg x-show="has(role)" class="h-2.5 w-2.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </span>
                                        <span x-text="cap(role)"></span>
                                    </button>
                                </template>
                                <template x-if="filtered.length === 0">
                                    <p class="px-3 py-2 text-xs text-zinc-400">No roles match.</p>
                                </template>
                            </div>

                            {{-- Footer --}}
                            <div x-show="selected.length > 0" class="border-t border-zinc-100 px-3 py-2">
                                <button type="button"
                                        @click="$wire.selectedRoles = []"
                                        class="text-xs text-zinc-400 hover:text-zinc-700">
                                    Clear selection
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('selectedRoles') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('selectedRoles.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Status</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button"
                                wire:click="$set('isActive', true)"
                                class="rounded-[5px] text-xs font-medium transition-colors {{ $isActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                            Active
                        </button>
                        <button type="button"
                                wire:click="$set('isActive', false)"
                                class="rounded-[5px] text-xs font-medium transition-colors {{ ! $isActive ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                            Inactive
                        </button>
                    </div>
                    @error('isActive') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('users.index') }}"
               class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:ring-offset-2">
                {{ $userId ? 'Update user' : 'Create user' }}
            </button>
        </div>

    </form>

</div>
