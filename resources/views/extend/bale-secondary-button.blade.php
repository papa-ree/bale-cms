@props(['label' => 'Button'])

@php
    $classes = 'capitalize inline-flex items-center py-3 px-4 text-sm font-medium text-gray-700 transition-all bg-gray-100
                border border-gray-300 drop-shadow-xl rounded-lg hs-accordion-toggle hover:bg-gray-200 hover:text-gray-900 focus:outline-hidden
                focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:text-gray-300
                dark:hover:bg-gray-900/20 dark:hover:text-white dark:focus:bg-gray-800/20 dark:focus:text-white
        ';
    // $classes = 'capitalize flex items-center justify-start text-gray-500 transition-all duration-500 rounded-lg
//     bg-gradient-to-tl tracking-wide bg-size-200 bg-pos-0 hover:bg-pos-100 from-white via-gray-50 to-white
//     dark:from-gray-700 dark:via-gray-800/10 dark:to-gray-700 border-2 border-gray-300 dark:border-gray-600
//     dark:text-gray-400 hover:dark:text-gray-200 hover:border-gray-500 hover:text-gray-900 focus:ring-4
//     focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium text-sm px-4 py-3 text-center
//     ';
@endphp

<div x-data="{ disabledButton: false }" @if ($attributes->has('spinner')) wire:loading.remove @endif>
    @if ($attributes->has('link'))
        <a wire:navigate.hover {{ $attributes->merge(['class' => $classes]) }}>
            {{ __($label) }}
        </a>
    @elseif ($attributes->has('link-reload'))
        <a {{ $attributes->merge(['class' => $classes]) }}>
            {{ __($label) }}
        </a>
    @else
        <button {{ $attributes->merge(['type' => 'submit', 'class' => $classes]) }} :disabled="disabledButton"
            x-on:disabling-button.window="disabledButton=$event.detail.params"
            @if ($attributes->has('useDisabledButton')) wire:dirty @endif>
            {{ __($label) }}
        </button>
    @endif
</div>

@if ($attributes->has('spinner'))
    <x-extend.bale-disabled-button :label="$label" spinner />
@endif

@if ($attributes->has('useDisabledButton'))
    <x-extend.bale-disabled-button :label="$label" useDisabledButton wire:dirty.remove />
@endif
