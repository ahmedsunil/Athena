{{--
    Forms: FileUpload
    -----------------
    File input with a dashed border, upload icon, and optional image preview.
--}}
@props([
    'label'       => 'Upload file',
    'accept'      => 'image/*',
    'hint'        => null,
    'previewUrl'  => null,
    'clearAction' => null,
])

<div class="space-y-3">
    @if($previewUrl)
        <div class="flex items-center gap-3">
                <img src="{{ $previewUrl }}" alt="Preview"
                 class="h-20 w-20 rounded-lg border border-zinc-200 object-cover">
            <div>
                <p class="text-xs font-medium text-zinc-700">New image selected</p>
                @if($clearAction)
                    <button type="button" wire:click="{{ $clearAction }}"
                            class="mt-1 text-xs text-red-500 hover:text-red-700">
                        Remove
                    </button>
                @endif
            </div>
        </div>
    @endif

    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-dashed border-zinc-300 p-4 text-sm text-zinc-500 transition-colors hover:border-zinc-400 hover:text-zinc-700">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
        </svg>
        <span>{{ $label }}</span>
        <input type="file" accept="{{ $accept }}" {{ $attributes }} class="hidden">
    </label>

    @if($hint)
        <p class="text-xs text-zinc-400">{{ $hint }}</p>
    @endif
</div>
