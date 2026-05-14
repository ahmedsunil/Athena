@php
    $currentRoute = request()->route()?->getName() ?? '';
    $academicsTabs = [
        ['label' => 'Overview',       'route' => 'cms.academics.overview', 'href' => route('cms.academics.overview')],
        ['label' => 'Key Stage Cards','route' => 'cms.academics.levels',   'href' => route('cms.academics.levels')],
    ];
@endphp

<div>
    <h1 class="admin-page-title text-lg">Academics</h1>
    <p class="admin-muted">Manage the public academics page content.</p>
</div>

<div class="flex items-center gap-1 pb-0 flex-wrap">
    @foreach($academicsTabs as $tab)
        @php $isActive = str_starts_with($currentRoute, $tab['route']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive ? 'text-zinc-50' : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
