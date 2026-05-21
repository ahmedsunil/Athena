@php use App\Models\Setting; @endphp
<div>
    <div class="mb-7">
        <p class="admin-eyebrow">School Website CMS</p>
        <h2 class="mt-2 admin-page-title text-lg">Sign in to Athena</h2>
        <p class="mt-2 admin-page-subtitle">Manage website content, announcements, events, and school updates.</p>
    </div>

    <form wire:submit="login" class="space-y-4">

        {{-- Email --}}
        <div>
            <label class="mb-1.5 block admin-label">Email address</label>
            <input type="email" wire:model="email" autocomplete="email" autofocus
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm transition focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('email') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="mb-1.5 block admin-label">Password</label>
            <input type="password" wire:model="password" autocomplete="current-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm transition focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('password') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs font-normal leading-5 text-zinc-600">
                <input type="checkbox" wire:model="remember"
                       class="h-3.5 w-3.5 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="admin-link-label text-zinc-950 hover:text-zinc-700">
                Forgot password?
            </a>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:ring-offset-2">
            Sign in
        </button>

    </form>

    @if(Setting::get('google_login_enabled') === '1')
        <div class="mt-5">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-zinc-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-2 text-zinc-400">or continue with</span>
                </div>
            </div>
            <a href="{{ route('auth.google') }}"
               class="mt-3 flex w-full items-center justify-center gap-2.5 rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium leading-5 text-zinc-700 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
                <svg viewBox="0 0 24 24" class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                        fill="#4285F4"/>
                    <path
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                        fill="#34A853"/>
                    <path
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"
                        fill="#FBBC05"/>
                    <path
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                        fill="#EA4335"/>
                </svg>
                Sign in with Google
            </a>
        </div>
    @endif


</div>
