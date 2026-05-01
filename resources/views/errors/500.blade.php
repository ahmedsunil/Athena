<x-guest-layout>
    <div class="text-center">
        <p class="text-4xl font-bold text-zinc-200">500</p>
        <h1 class="mt-2 admin-page-title">Server error</h1>
        <p class="mt-1 admin-body-muted">Something went wrong on our end. Please try again shortly.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-1.5 admin-link-label text-zinc-600 hover:text-zinc-900">
            ← Back to home
        </a>
    </div>
</x-guest-layout>
