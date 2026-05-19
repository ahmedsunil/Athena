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
                    <label class="mb-1.5 block admin-label">Name (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="e.g. Ahmed Ali"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Name (ދިވެހި)</label>
                    <input type="text" wire:model="name_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Designation (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="designation_en" placeholder="e.g. Head of Mathematics"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('designation_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Designation (ދިވެހި)</label>
                    <input type="text" wire:model="designation_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('designation_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Education --}}
            <div>
                <label class="mb-1.5 block admin-label">Education (English) <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="education_en" placeholder="e.g. B.Sc Mathematics · M.Ed Curriculum"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('education_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Education (ދިވެހި)</label>
                <input type="text" wire:model="education_dv" dir="rtl"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('education_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                @elseif($existing_photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\StaffMember::resolvePhotoUrl($existing_photo) }}" alt="" class="h-12 w-12 rounded-full object-cover">
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
                        <div class="space-y-1.5 border border-zinc-100 rounded-lg p-2">
                            {{-- English row --}}
                            <div class="grid gap-2 sm:grid-cols-3 items-start">
                                <div>
                                    <input type="text" wire:model="workExperiences.{{ $i }}.title" placeholder="Job Title (EN)"
                                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                    @error("workExperiences.{$i}.title") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <input type="text" wire:model="workExperiences.{{ $i }}.institution" placeholder="Institution (EN)"
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
                            {{-- Dhivehi row --}}
                            <div class="grid gap-2 sm:grid-cols-3 items-start">
                                <div>
                                    <input type="text" wire:model="workExperiences.{{ $i }}.title_dv" placeholder="Job Title (ދިވެހި)" dir="rtl"
                                           class="h-9 w-full rounded-md border border-zinc-100 bg-zinc-50 px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                    @error("workExperiences.{$i}.title_dv") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <input type="text" wire:model="workExperiences.{{ $i }}.institution_dv" placeholder="Institution (ދިވެހި)" dir="rtl"
                                           class="h-9 w-full rounded-md border border-zinc-100 bg-zinc-50 px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                    @error("workExperiences.{$i}.institution_dv") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <input type="text" wire:model="workExperiences.{{ $i }}.period_dv" placeholder="މ.ދ. (ދިވެހި)" dir="rtl"
                                           class="h-9 w-full rounded-md border border-zinc-100 bg-zinc-50 px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                    @error("workExperiences.{$i}.period_dv") <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                                </div>
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
                            {{ $s->section === 'senior_management' ? 'bg-[#002366]/10 text-[#002366]' : ($s->section === 'academic' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
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
