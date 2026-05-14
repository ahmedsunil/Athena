{{-- Variables: $member (StaffMember), $accentColor ('rose'|'sky'|'amber') --}}
@php
    $borderMap = [
        'rose'  => 'border-rose-300',
        'sky'   => 'border-sky-300',
        'amber' => 'border-amber-300',
    ];
    $accentBorder = $borderMap[$accentColor] ?? 'border-slate-300';
@endphp

<div x-data="{ expanded: false }">
    <div
        @click="expanded = !expanded"
        class="bg-white border border-slate-200 rounded-2xl p-4 cursor-pointer hover:border-slate-300 hover:shadow-sm transition-all duration-150 select-none group"
    >
        {{-- Avatar --}}
        <div class="mb-3">
            <div class="w-14 h-14 rounded-full overflow-hidden ring-2 ring-slate-100 group-hover:ring-slate-200 transition-all">
                @if($member->photo_url)
                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover object-top">
                @else
                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-500 text-xl font-black">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Info --}}
        <p class="font-bold text-slate-900 text-sm leading-snug">{{ $member->name }}</p>
        <p class="text-xs text-rose-600 font-semibold mt-0.5 leading-snug">{{ $member->designation }}</p>

        {{-- Expand hint --}}
        @if($member->education || !empty($member->work_experiences))
            <div class="mt-2.5 flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-400 leading-none" x-show="!expanded">Details</span>
                <span class="text-[10px] font-semibold text-slate-400 leading-none" x-show="expanded" x-cloak>Close</span>
                <svg
                    class="w-3.5 h-3.5 text-slate-300 transition-transform duration-200"
                    :class="expanded ? 'rotate-180' : ''"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        @endif
    </div>

    {{-- Inline detail panel --}}
    <div
        x-show="expanded"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
        class="border border-t-0 border-slate-200 rounded-b-2xl bg-slate-50 px-4 pb-4 pt-3 -mt-2 space-y-3"
    >
        @if($member->education)
            <div class="flex items-start gap-2">
                <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422A12.083 12.083 0 0121 21.5H3a12.083 12.083 0 012.84-10.922L12 14z"/>
                </svg>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $member->education }}</p>
            </div>
        @endif

        @if(!empty($member->work_experiences))
            <div class="space-y-2">
                @foreach($member->work_experiences as $exp)
                    <div class="border-l-2 {{ $accentBorder }} pl-2.5">
                        <p class="text-xs font-semibold text-slate-800 leading-snug">{{ $exp['title'] }}</p>
                        <p class="text-[11px] text-slate-500 leading-snug">{{ $exp['institution'] }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $exp['period'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
