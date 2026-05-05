<div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm ring-1 ring-zinc-100/70">

    {{-- Mobile card list --}}
    @if(isset($mobile))
        <ul class="divide-y divide-zinc-100 md:hidden">
            {{ $mobile }}
        </ul>
    @endif

    {{-- Desktop table --}}
    @if(isset($head) || isset($body))
        <div class="hidden overflow-x-auto md:block">
            <table class="admin-table">
                @if(isset($head))
                    <thead>
                        <tr class="admin-table-head-row">
                            {{ $head }}
                        </tr>
                    </thead>
                @endif
                @if(isset($body))
                    <tbody class="admin-table-body">
                        {{ $body }}
                    </tbody>
                @endif
            </table>
        </div>
    @endif

    {{-- Fallback slot (empty state or custom content) --}}
    @if(isset($slot) && $slot->isNotEmpty())
        {{ $slot }}
    @endif

    {{-- Pagination --}}
    @if(isset($pagination))
        <div class="border-t border-zinc-100 px-5 py-3">
            {{ $pagination }}
        </div>
    @endif

</div>
