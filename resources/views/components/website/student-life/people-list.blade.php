@props([
    'people' => [],
    'initialsFor',
])

@php
    $filteredPeople = collect($people)->filter(fn ($person) => filled(data_get($person, 'name')));
@endphp

@if($filteredPeople->isNotEmpty())
    <div class="grid gap-3 md:grid-cols-2">
        @foreach($filteredPeople as $person)
            @php
                $isModel = is_object($person);
                $name = $isModel ? $person->name : data_get($person, 'name');
                $role = $isModel ? $person->designation : data_get($person, 'role');
                $grade = $isModel ? $person->grade : data_get($person, 'grade');
                $avatarUrl = $isModel ? $person->avatar_url : null;
                $initials = $isModel ? $person->initials : $initialsFor($name);
                $isRed = ! $isModel && data_get($person, 'color', 'blue') === 'red';
            @endphp
            <div class="flex items-center gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4">
                <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded-full {{ $isRed ? 'bg-[#B21F2D]/10 text-[#B21F2D]' : 'bg-[#002366]/10 text-[#002366]' }}">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $name }}" class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-xs font-black">
                            {{ $initials }}
                        </div>
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-900">{{ $name }}</p>
                    <p class="truncate text-xs font-semibold text-[#002366]">
                        {{ $role }}
                        @if($grade)
                            <span class="text-slate-400">· {{ $grade }}</span>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach
    </div>
@endif
