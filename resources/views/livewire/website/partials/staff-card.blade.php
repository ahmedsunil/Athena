{{-- Variables: $member (StaffMember), $accentColor ('rose'|'sky'|'amber') --}}
@php
    $colorMap = [
        'rose'  => ['ring' => 'ring-rose-100',  'border' => 'border-rose-300',  'text' => 'text-rose-600',  'bg' => 'bg-rose-50'],
        'sky'   => ['ring' => 'ring-sky-100',   'border' => 'border-sky-300',   'text' => 'text-sky-600',   'bg' => 'bg-sky-50'],
        'amber' => ['ring' => 'ring-amber-100', 'border' => 'border-amber-300', 'text' => 'text-amber-600', 'bg' => 'bg-amber-50'],
    ];
    $c = $colorMap[$accentColor] ?? $colorMap['sky'];
@endphp

<div x-data="{ expanded: false }">
    {{-- Card --}}
    <div
        @click="expanded = !expanded"
        class="bg-white border border-slate-200 rounded-2xl p-4 cursor-pointer hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 select-none"
        :class="expanded ? 'rounded-b-none border-b-0 shadow-md' : ''"
    >
        <div class="mb-3">
            <div class="w-12 h-12 rounded-full overflow-hidden ring-4 {{ $c['ring'] }}">
                @if($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover object-top">
                @else
                    <div class="w-full h-full {{ $c['bg'] }} flex items-center justify-center {{ $c['text'] }} text-lg font-black">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>
        <p class="font-bold text-slate-900 text-sm leading-snug">{{ $member->name }}</p>
        <p class="text-xs {{ $c['text'] }} font-semibold mt-0.5">{{ $member->designation }}</p>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-semibold text-slate-400" x-show="!expanded">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            View details
        </div>
        <div class="mt-2 flex items-center gap-1 text-[10px] font-semibold {{ $c['text'] }}" x-show="expanded">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
            Close
        </div>
    </div>

    {{-- Inline detail panel --}}
    <div
        x-show="expanded"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="bg-slate-50 border border-slate-200 border-t-0 rounded-b-2xl px-4 pb-4 pt-3"
    >
        @if($member->education)
            <p class="text-xs text-slate-500 mb-3">
                <span class="font-semibold text-slate-700">Education:</span> {{ $member->education }}
            </p>
        @endif

        @if(!empty($member->work_experiences))
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Work Experience</p>
            <div class="space-y-2">
                @foreach($member->work_experiences as $exp)
                    <div class="border-l-2 {{ $c['border'] }} pl-3">
                        <p class="text-xs font-semibold text-slate-800">{{ $exp['title'] }}</p>
                        <p class="text-xs text-slate-500">{{ $exp['institution'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $exp['period'] }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-xs text-slate-400 italic">No experience details added.</p>
        @endif
    </div>
</div>
