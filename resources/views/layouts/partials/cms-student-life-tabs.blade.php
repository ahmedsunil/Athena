@php
    $currentRoute = request()->route()?->getName() ?? '';
    $tabs = [
        ['label' => 'Student Council', 'routes' => ['cms.clubs', 'cms.houses'], 'href' => route('cms.clubs')],
        ['label' => 'Prefects',        'routes' => ['cms.prefects'],                         'href' => route('cms.prefects')],
        ['label' => 'Uniform Bodies',  'routes' => ['cms.uniform-bodies'],                   'href' => route('cms.uniform-bodies')],
        ['label' => 'People History',  'routes' => ['cms.people'],                          'href' => route('cms.people')],
    ];
    $councilSubTabs = [
        ['label' => 'Clubs',  'route' => 'cms.clubs',  'href' => route('cms.clubs')],
        ['label' => 'Houses', 'route' => 'cms.houses', 'href' => route('cms.houses')],
    ];
    $inCouncil = in_array($currentRoute, ['cms.clubs', 'cms.houses']);
@endphp

<div>
    <h1 class="admin-page-title text-lg">Student Life</h1>
    <p class="admin-muted">Manage the public student life page content.</p>
</div>

<div class="flex items-center gap-1 pb-0 flex-wrap">
    @foreach($tabs as $tab)
        @php $isActive = in_array($currentRoute, $tab['routes']); @endphp
        <a href="{{ $tab['href'] }}"
           class="relative px-3 py-2 text-sm font-medium transition-colors bg-zinc-800 rounded-md
                  {{ $isActive ? 'text-zinc-50' : 'text-zinc-500 hover:text-zinc-100' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>

@if($inCouncil)
    <div class="flex items-center gap-1 flex-wrap">
        @foreach($councilSubTabs as $sub)
            @php $subActive = $currentRoute === $sub['route']; @endphp
            <a href="{{ $sub['href'] }}"
               class="px-3 py-1.5 text-xs font-medium rounded-md transition-colors
                      {{ $subActive ? 'bg-zinc-700 text-zinc-100' : 'text-zinc-500 hover:text-zinc-300' }}">
                {{ $sub['label'] }}
            </a>
        @endforeach
    </div>
@endif
