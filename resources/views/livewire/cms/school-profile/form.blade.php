@php use Illuminate\Support\Facades\Storage; @endphp
<div class="mx-auto space-y-4">
    <div>
        <h1 class="admin-page-title">School Profile</h1>
        <p class="admin-page-subtitle">Manage the public school profile and contact details.</p>
    </div>

    <form wire:submit="save" class="space-y-4">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="space-y-4 lg:col-span-2">
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <div class="mb-4">
                        <h2 class="admin-section-title">Basic Information</h2>
                        <p class="admin-caption">Core identity fields for the school.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.forms.form-field label="School Name" field="name" required>
                            <x-admin.forms.text-input wire:model="name" placeholder="Bright Future Academy"/>
                        </x-admin.forms.form-field>

                        <x-admin.forms.form-field label="Founded Year" field="founded_year" required>
                            <x-admin.forms.text-input wire:model="founded_year" type="number" min="1800"
                                                      max="{{ now()->year }}" placeholder="1995"/>
                        </x-admin.forms.form-field>

                        <x-admin.forms.form-field label="Motto" field="motto">
                            <x-admin.forms.text-input wire:model="motto" placeholder="Educating Tomorrow's Leaders"/>
                        </x-admin.forms.form-field>

                        <x-admin.forms.form-field label="Tagline" field="tagline">
                            <x-admin.forms.text-input wire:model="tagline" placeholder="Excellence in Education"/>
                        </x-admin.forms.form-field>
                    </div>

                    <div class="mt-4">
                        <x-admin.forms.form-field label="Description" field="description">
                            <x-admin.forms.textarea wire:model="description" rows="4"
                                                    placeholder="Short public description of the school."/>
                        </x-admin.forms.form-field>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <div class="mb-4">
                        <h2 class="admin-section-title">Mission & Vision</h2>
                        <p class="admin-caption">Statements displayed on public school profile surfaces.</p>
                    </div>

                    <div class="space-y-4">
                        <x-admin.forms.form-field label="Mission Statement" field="mission_statement">
                            <x-admin.forms.textarea wire:model="mission_statement" rows="4"
                                                    placeholder="Describe the school's mission."/>
                        </x-admin.forms.form-field>

                        <x-admin.forms.form-field label="Vision Statement" field="vision_statement">
                            <x-admin.forms.textarea wire:model="vision_statement" rows="4"
                                                    placeholder="Describe the school's vision."/>
                        </x-admin.forms.form-field>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <div class="mb-4">
                        <h2 class="admin-section-title">Contact Details</h2>
                        <p class="admin-caption">Public address, phone, and email information.</p>
                    </div>

                    <div class="space-y-4">
                        <x-admin.forms.form-field label="Address" field="contact_address" required>
                            <x-admin.forms.textarea wire:model="contact_address" rows="3"
                                                    placeholder="123 Education Street, City, Country"/>
                        </x-admin.forms.form-field>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <x-admin.forms.form-field label="Phone" field="contact_phone" required>
                                <x-admin.forms.text-input wire:model="contact_phone" type="tel"
                                                          placeholder="+960 123 4567"/>
                            </x-admin.forms.form-field>

                            <x-admin.forms.form-field label="Email" field="contact_email" required>
                                <x-admin.forms.text-input wire:model="contact_email" type="email"
                                                          placeholder="info@example.edu"/>
                            </x-admin.forms.form-field>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <div class="mb-4">
                        <h2 class="admin-section-title">Brand Assets</h2>
                        <p class="admin-caption">Logo and hero image used by the API profile.</p>
                    </div>

                    <div class="space-y-5">
                        <x-admin.forms.form-field label="Logo" field="logo" :required="! $logo_path">
                            @if($logo)
                                <div class="mb-3 flex items-center gap-3">
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview"
                                         class="h-16 w-16 rounded-lg border border-zinc-200 object-cover">
                                    <button type="button" wire:click="$set('logo', null)"
                                            class="admin-link-label text-red-500 hover:text-red-700">
                                        Remove
                                    </button>
                                </div>
                            @elseif($logo_path)
                                <div class="mb-3 flex items-center gap-3">
                                    <img
                                        src="{{ Storage::disk('public')->url($logo_path) }}"
                                        alt="Current logo"
                                        class="h-16 w-16 rounded-lg border border-zinc-200 object-cover">
                                    <p class="admin-caption">Current logo</p>
                                </div>
                            @endif

                            <x-admin.forms.file-upload
                                wire:model="logo"
                                :label="$logo_path ? 'Change logo' : 'Upload logo'"
                                hint="JPG, PNG, or WebP. Max 2MB."
                            />
                        </x-admin.forms.form-field>

                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="cursor-not-allowed opacity-60"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-950 px-4 py-2.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800 disabled:opacity-60">
                        <svg wire:loading wire:target="save" class="h-4 w-4 animate-spin" fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Save Profile
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
