@props(['label'])

@if ($attributes->has('spinner'))
    <button type="button" disabled
        class="flex items-center justify-start px-4 py-3 text-sm tracking-wide text-gray-400 capitalize bg-gray-100 border-2 border-gray-200 rounded-lg"
        wire:loading>
        {{ __($label) }}
    </button>
@endif

@if ($attributes->has('useDisabledButton'))
    <button disabled {{ $attributes->merge(['type' => 'button']) }}
        class="flex items-center justify-start px-4 py-3 text-sm tracking-wide text-gray-400 capitalize bg-gray-100 border-2 border-gray-200 rounded-lg">
        {{ __($label) }}
    </button>
@endif
