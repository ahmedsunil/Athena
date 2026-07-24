<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div>
        {{--        <h1 class="admin-page-title">School Profile</h1>--}}
        <p class="admin-muted">Shared identity used across the public website.</p>
    </div>

    <form wire:submit="save" class="space-y-4">

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Identity</h3>
                <p class="admin-caption">School name, motto, and short description.</p>
            </div>
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block admin-label">School name (English)</label>
                        <input type="text" wire:model="school_name_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('school_name_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block admin-label">Motto (English)</label>
                        <input type="text" wire:model="motto_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('motto_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Short description (English)</label>
                    <textarea wire:model="short_description_en" rows="3"
                              class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                    @error('short_description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Logo</label>
                    @if($logo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo" class="h-16 w-16 rounded-lg object-cover">
                            <button type="button" wire:click="removeLogo"
                                    class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @elseif($existing_logo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ \App\Models\SchoolProfile::resolveMediaUrl($existing_logo) }}" alt="Logo" class="h-16 w-16 rounded-lg object-cover">
                            <button type="button" wire:click="removeLogo"
                                    class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @endif
                    <input type="file" wire:model.live="logo" accept="image/*"
                           class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                    @error('logo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Contact</h3>
                <p class="admin-caption">Email, phone, and physical address.</p>
            </div>
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block admin-label">Email</label>
                        <input type="email" wire:model="email"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('email') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block admin-label">Phone</label>
                        <input type="text" wire:model="phone"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('phone') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Address (English)</label>
                    <input type="text" wire:model="address_en"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('address_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block admin-label">Island (English)</label>
                        <input type="text" wire:model="school_island_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('school_island_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block admin-label">Atoll (English)</label>
                        <input type="text" wire:model="atoll_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('atoll_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block admin-label">Country (English)</label>
                        <input type="text" wire:model="country_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('country_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4">
                <h3 class="admin-section-title">Principal</h3>
                <p class="admin-caption">Principal's name, designation, message, and photo.</p>
            </div>
            <div class="space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block admin-label">Name (English)</label>
                        <input type="text" wire:model="principal_name_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('principal_name_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block admin-label">Designation (English)</label>
                        <input type="text" wire:model="principal_designation_en"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('principal_designation_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Message (English)</label>
                    <textarea wire:model="principal_message_en" rows="4"
                              class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                    @error('principal_message_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Photo</label>
                    @if($principal_photo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ $principal_photo->temporaryUrl() }}" alt="Principal" class="h-16 w-16 rounded-full object-cover">
                            <button type="button" wire:click="removePrincipalPhoto"
                                    class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @elseif($existing_principal_photo)
                        <div class="relative mb-2 inline-block">
                            <img src="{{ \App\Models\SchoolProfile::resolveMediaUrl($existing_principal_photo) }}" alt="Principal" class="h-16 w-16 rounded-full object-cover">
                            <button type="button" wire:click="removePrincipalPhoto"
                                    class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                    @endif
                    <input type="file" wire:model.live="principal_photo" accept="image/*"
                           class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                    @error('principal_photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end">
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:ring-offset-2">
                Save profile
            </button>
        </div>

    </form>

</div>
