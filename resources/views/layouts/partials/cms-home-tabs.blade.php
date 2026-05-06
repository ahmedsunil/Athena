@php
    $currentRoute = request()->route()?->getName() ?? '';
    $homeTabs = [
        ['label' => 'School Profile',     'route' => 'cms.school-profile',     'href' => route('cms.school-profile')],
        ['label' => 'Footer Links',        'route' => 'cms.footer-links',        'href' => route('cms.footer-links')],
        ['label' => 'Contact',             'route' => 'cms.contact-submissions', 'href' => route('cms.contact-submissions')],
        ['label' => 'Slides',              'route' => 'cms.home.slides',         'href' => route('cms.home.slides')],
        ['label' => 'Stats',               'route' => 'cms.home.stats',          'href' => route('cms.home.stats')],
        ['label' => 'Quick Access',        'route' => 'cms.home.quick-access',   'href' => route('cms.home.quick-access')],
        ['label' => 'Testimonials',        'route' => 'cms.home.testimonials',   'href' => route('cms.home.testimonials')],
    ];
@endphp

<div>
    <h1 class="admin-page-title text-lg">Home</h1>
    <p class="admin-muted">Manage the public home page content, school identity, contact details, and supporting
        links.</p>
</div>

<div class="flex items-center gap-1 pb-0">
    @foreach($homeTabs as $tab)
        @php $isActive = str_starts_with($currentRoute, $tab['route']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive
                      ? 'text-zinc-50 after:absolute after:inset-x-0'
                      : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
