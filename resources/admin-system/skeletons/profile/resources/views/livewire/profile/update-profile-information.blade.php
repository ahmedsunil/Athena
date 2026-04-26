<div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
    <div class="mb-4">
        <h2 class="text-sm font-semibold text-stone-900">Profile Information</h2>
        <p class="mt-0.5 text-xs text-stone-400">Update your name and email address.</p>
    </div>

    <form wire:submit="updateProfileInformation">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-xs font-medium text-stone-700">Name</label>
                <input
                    id="name"
                    type="text"
                    wire:model="name"
                    autocomplete="name"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                    placeholder="Your name"
                >
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-xs font-medium text-stone-700">Email Address</label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    autocomplete="email"
                    class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
            <div class="mt-3 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-700">
                Your email address is unverified.
                <form method="POST" action="{{ route('verification.send') }}" class="inline">
                    @csrf
                    <button type="submit" class="ml-1 underline hover:text-amber-900 focus:outline-none">
                        Resend verification email
                    </button>
                </form>
            </div>
        @endif

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
