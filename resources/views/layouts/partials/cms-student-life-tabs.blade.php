@php
    $currentRoute = request()->route()?->getName() ?? '';
    $tabs = [
        ['label' => 'Clubs',          'route' => 'cms.student-life.clubs',          'href' => route('cms.student-life.clubs')],
        ['label' => 'Prefects',       'route' => 'cms.student-life.prefects',       'href' => route('cms.student-life.prefects')],
        ['label' => 'Houses',         'route' => 'cms.student-life.houses',         'href' => route('cms.student-life.houses')],
        ['label' => 'Uniform Bodies', 'route' => 'cms.student-life.uniform-bodies', 'href' => route('cms.student-life.uniform-bodies')],
    ];
@endphp

<div>
    <h1 class="admin-page-title text-lg">Student Life</h1>
    <p class="admin-muted">Manage the public student life page content.</p>
</div>

<div class="flex items-center gap-1 pb-0 flex-wrap">
    @foreach($tabs as $tab)
        @php $isActive = str_starts_with($currentRoute, $tab['route']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive ? 'text-zinc-50' : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
