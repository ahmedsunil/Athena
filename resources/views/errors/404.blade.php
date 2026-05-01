<x-guest-layout>
    <div class="text-center">
        <p class="text-4xl font-bold text-zinc-200">404</p>
        <h1 class="mt-2 admin-page-title">Page not found</h1>
        <p class="mt-1 admin-body-muted">The page you're looking for doesn't exist.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-flex items-center gap-1.5 admin-link-label text-zinc-600 hover:text-zinc-900">
            ← Back to home
        </a>
    </div>
</x-guest-layout>
