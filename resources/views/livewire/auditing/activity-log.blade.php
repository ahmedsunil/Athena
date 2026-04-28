<div>
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-base font-semibold text-zinc-950">Activity Log</h1>
        <p class="mt-1 text-xs text-zinc-500">Track all system events and changes.</p>
    </div>

    {{-- Filters --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search user or event…"
               class="w-48 rounded-lg border border-zinc-300 px-3 py-1.5 text-xs text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">

        <select wire:model.live="eventFilter"
                class="rounded-lg border border-zinc-300 px-3 py-1.5 text-xs text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            <option value="">All events</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
        </select>

        <input type="date" wire:model.live="dateFrom"
               class="rounded-lg border border-zinc-300 px-3 py-1.5 text-xs text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
        <input type="date" wire:model.live="dateTo"
               class="rounded-lg border border-zinc-300 px-3 py-1.5 text-xs text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">

        @if($search || $eventFilter || $dateFrom || $dateTo)
            <button wire:click="$set('search','');$set('eventFilter','');$set('dateFrom','');$set('dateTo','')"
                    class="rounded-lg border border-zinc-300 px-3 py-1.5 text-xs font-medium text-zinc-600 hover:bg-zinc-50">
                Clear
            </button>
        @endif
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm overflow-hidden">
        @if($logs->isEmpty())
            <div class="py-16 text-center">
                <svg class="mx-auto mb-3 h-8 w-8 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                </svg>
                <p class="text-sm text-zinc-400">No activity found.</p>
            </div>
        @else
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-zinc-200 bg-zinc-50">
                        <th class="px-4 py-3 text-left font-semibold text-zinc-500">User</th>
                        <th class="px-4 py-3 text-left font-semibold text-zinc-500">Event</th>
                        <th class="px-4 py-3 text-left font-semibold text-zinc-500">Subject</th>
                        <th class="px-4 py-3 text-left font-semibold text-zinc-500">Changes</th>
                        <th class="px-4 py-3 text-right font-semibold text-zinc-500">When</th>
                        <th class="px-4 py-3 text-right font-semibold text-zinc-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @foreach($logs as $log)
                        @php
                            $causer    = $log->causer;
                            $initial   = strtoupper(substr($causer?->name ?? '?', 0, 1));
                            $eventColor = match($log->event) {
                                'created' => 'bg-emerald-100 text-emerald-700',
                                'updated' => 'bg-blue-100 text-blue-700',
                                'deleted' => 'bg-red-100 text-red-700',
                                default   => 'bg-zinc-100 text-zinc-600',
                            };
                            $subject   = $log->subject;
                            $subjectName = $subject?->name ?? $subject?->title ?? ('#' . $log->subject_id);
                            $subjectType = $log->subject_type ? class_basename($log->subject_type) : '—';
                            $old = $log->properties['old'] ?? [];
                            $new = $log->properties['attributes'] ?? [];
                        @endphp
                        <tr class="hover:bg-zinc-50">
                            {{-- Causer --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-[10px] font-semibold text-zinc-600">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-zinc-950">{{ $causer?->name ?? 'System' }}</p>
                                        <p class="text-zinc-400">{{ $causer?->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Event --}}
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $eventColor }}">
                                    {{ $log->event ?? $log->description }}
                                </span>
                            </td>

                            {{-- Subject --}}
                            <td class="px-4 py-3">
                                <p class="font-medium text-zinc-700">{{ $subjectType }}</p>
                                <p class="text-zinc-400">{{ $subjectName }}</p>
                            </td>

                            {{-- Changes --}}
                            <td class="px-4 py-3 max-w-xs">
                                @if(count($old) || count($new))
                                    <div class="space-y-0.5">
                                        @foreach($new as $field => $val)
                                            @if(array_key_exists($field, $old))
                                                <p class="truncate text-zinc-500">
                                                    <span class="font-medium text-zinc-700">{{ $field }}:</span>
                                                    <span class="line-through text-red-400">{{ is_bool($old[$field]) ? ($old[$field] ? 'true' : 'false') : $old[$field] }}</span>
                                                    → <span class="text-emerald-600">{{ is_bool($val) ? ($val ? 'true' : 'false') : $val }}</span>
                                                </p>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-zinc-300">—</span>
                                @endif
                            </td>

                            {{-- Timestamp --}}
                            <td class="px-4 py-3 text-right text-zinc-400 whitespace-nowrap">
                                {{ $log->created_at->format('M j, Y g:i A') }}
                            </td>

                            {{-- Action --}}
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('app.activity.show', $log->id) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-200 px-2.5 py-1.5 text-xs font-medium text-zinc-700 transition hover:border-zinc-300 hover:bg-zinc-50 hover:text-zinc-950">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12s-3.75 6.75-9.75 6.75S2.25 12 2.25 12z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($logs->hasPages())
                <div class="border-t border-zinc-100 px-4 py-3">
                    {{ $logs->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
