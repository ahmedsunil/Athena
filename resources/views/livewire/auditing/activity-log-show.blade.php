<div>
    @php
        $causer = $activity->causer;
        $subject = $activity->subject;
        $causerName = $causer?->name ?? 'System';
        $causerEmail = $causer?->email ?? null;
        $causerType = $activity->causer_type ? class_basename($activity->causer_type) : 'None';
        $subjectType = $activity->subject_type ? class_basename($activity->subject_type) : 'None';
        $subjectName = $subject?->name ?? $subject?->title ?? ($activity->subject_id ? '#'.$activity->subject_id : 'No subject');
        $changeRows = $this->changeRows();
        $extraProperties = $this->extraProperties();
        $rawAttributeChanges = $this->rawAttributeChanges();
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="flex min-w-0 items-start gap-3">
            <a href="{{ route('app.activity') }}"
               class="mt-1 inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-zinc-200 text-zinc-500 transition hover:bg-zinc-50 hover:text-zinc-950"
               aria-label="Back to activity log">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-base font-semibold text-zinc-950 sm:text-lg">Activity #{{ $activity->id }}</h1>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $this->eventColor() }}">
                        {{ $activity->event ?? 'recorded' }}
                    </span>
                </div>
                <p class="mt-1 text-xs text-zinc-500">
                    {{ $activity->description }} recorded on {{ $activity->created_at->format('M j, Y \a\t g:i A') }}
                </p>
            </div>
        </div>

        <a href="{{ route('app.activity') }}"
           class="inline-flex items-center justify-center rounded-lg border border-zinc-200 px-3 py-2 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50 hover:text-zinc-950">
            Back to Log
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-zinc-950">Change Summary</h2>
                    <p class="mt-1 text-xs text-zinc-500">Field-level before and after values captured for this activity.</p>
                </div>

                @if(count($changeRows))
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] text-xs">
                            <thead>
                                <tr class="border-b border-zinc-100 bg-zinc-50 text-left font-semibold text-zinc-500">
                                    <th class="px-5 py-3">Field</th>
                                    <th class="px-5 py-3">Previous Value</th>
                                    <th class="px-5 py-3">New Value</th>
                                    <th class="px-5 py-3 text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @foreach($changeRows as $row)
                                    <tr class="align-top hover:bg-zinc-50">
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-zinc-950">{{ $row['field'] }}</p>
                                            <p class="mt-1 text-[11px] text-zinc-400">Tracked attribute</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <pre class="max-h-44 overflow-auto whitespace-pre-wrap break-words rounded-lg bg-zinc-50 p-3 font-mono text-[11px] leading-5 text-zinc-600">{{ $row['old'] }}</pre>
                                            <p class="mt-1 text-[11px] text-zinc-400">Type: {{ $row['old_type'] }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <pre class="max-h-44 overflow-auto whitespace-pre-wrap break-words rounded-lg bg-zinc-50 p-3 font-mono text-[11px] leading-5 text-zinc-600">{{ $row['new'] }}</pre>
                                            <p class="mt-1 text-[11px] text-zinc-400">Type: {{ $row['new_type'] }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $this->statusColor($row['status']) }}">
                                                {{ $row['status'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-5 py-12 text-center">
                        <svg class="mx-auto mb-3 h-8 w-8 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        <p class="text-sm font-medium text-zinc-600">No field-level changes were stored.</p>
                        <p class="mt-1 text-xs text-zinc-400">The activity still has event, actor, subject, and metadata details below.</p>
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                <div class="border-b border-zinc-100 px-5 py-4">
                    <h2 class="text-sm font-semibold text-zinc-950">Additional Properties</h2>
                    <p class="mt-1 text-xs text-zinc-500">Extra metadata stored with the audit entry outside of old and new attributes.</p>
                </div>

                @if(count($extraProperties))
                    <div class="divide-y divide-zinc-100">
                        @foreach($extraProperties as $key => $value)
                            <div class="grid gap-3 px-5 py-4 sm:grid-cols-4">
                                <div>
                                    <p class="text-xs font-medium text-zinc-950">{{ $key }}</p>
                                    <p class="mt-1 text-[11px] text-zinc-400">Type: {{ $this->valueType($value) }}</p>
                                </div>
                                <pre class="sm:col-span-3 max-h-56 overflow-auto whitespace-pre-wrap break-words rounded-lg bg-zinc-50 p-3 font-mono text-[11px] leading-5 text-zinc-600">{{ $this->formatValue((string) $key, $value) }}</pre>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-5 py-8 text-sm text-zinc-400">No additional properties were stored for this activity.</div>
                @endif
            </section>

            @if(count($rawAttributeChanges))
                <section class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
                    <div class="border-b border-zinc-100 px-5 py-4">
                        <h2 class="text-sm font-semibold text-zinc-950">Raw Attribute Changes</h2>
                        <p class="mt-1 text-xs text-zinc-500">Database-level attribute_changes payload for troubleshooting.</p>
                    </div>
                    <pre class="max-h-96 overflow-auto whitespace-pre-wrap break-words p-5 font-mono text-[11px] leading-5 text-zinc-600">{{ json_encode($rawAttributeChanges, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                </section>
            @endif
        </div>

        <aside class="space-y-4">
            <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-zinc-950">Overview</h2>
                <dl class="mt-4 space-y-3 text-xs">
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-500">Description</dt>
                        <dd class="max-w-[12rem] text-right font-medium text-zinc-950">{{ $activity->description }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Log Name</dt>
                        <dd class="font-medium text-zinc-950">{{ $activity->log_name ?? 'default' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Event</dt>
                        <dd class="font-medium text-zinc-950">{{ $activity->event ?? 'recorded' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Created</dt>
                        <dd class="text-right font-medium text-zinc-950">{{ $activity->created_at->format('M j, Y g:i A') }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Relative</dt>
                        <dd class="font-medium text-zinc-950">{{ $activity->created_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-zinc-950">Actor</h2>
                <div class="mt-4 flex items-start gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-semibold text-zinc-600">
                        {{ strtoupper(substr($causerName, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-zinc-950">{{ $causerName }}</p>
                        @if($causerEmail)
                            <p class="truncate text-xs text-zinc-500">{{ $causerEmail }}</p>
                        @endif
                        <p class="mt-1 text-[11px] text-zinc-400">{{ $causerType }} {{ $activity->causer_id ? '#'.$activity->causer_id : '' }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-zinc-950">Subject</h2>
                <dl class="mt-4 space-y-3 text-xs">
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-500">Record</dt>
                        <dd class="max-w-[12rem] text-right font-medium text-zinc-950">{{ $subjectName }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Type</dt>
                        <dd class="font-medium text-zinc-950">{{ $subjectType }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">ID</dt>
                        <dd class="font-medium text-zinc-950">{{ $activity->subject_id ?? 'None' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Available</dt>
                        <dd class="font-medium {{ $subject ? 'text-emerald-700' : 'text-zinc-500' }}">
                            {{ $subject ? 'Yes' : 'No' }}
                        </dd>
                    </div>
                </dl>
            </section>

            <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-semibold text-zinc-950">Technical Details</h2>
                <dl class="mt-4 space-y-3 text-xs">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Activity ID</dt>
                        <dd class="font-mono font-medium text-zinc-950">{{ $activity->id }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-500">Subject Class</dt>
                        <dd class="max-w-[12rem] break-words text-right font-mono text-[11px] text-zinc-700">{{ $activity->subject_type ?? 'None' }}</dd>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <dt class="text-zinc-500">Causer Class</dt>
                        <dd class="max-w-[12rem] break-words text-right font-mono text-[11px] text-zinc-700">{{ $activity->causer_type ?? 'None' }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-zinc-500">Updated</dt>
                        <dd class="text-right font-medium text-zinc-950">{{ $activity->updated_at->format('M j, Y g:i A') }}</dd>
                    </div>
                </dl>
            </section>
        </aside>
    </div>
</div>
