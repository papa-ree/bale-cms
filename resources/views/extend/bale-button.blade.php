@props(['label' => 'Button'])

@php
    $classes =
        'px-4 py-3 text-center select-none border-2 border-blue-300 dark:border-1 dark:border-blue-300/70 text-white text-sm antialiased items-center flex tracking-wide transition-all duration-500 rounded-lg bg-gradient-to-tl from-emerald-300 via-blue-500 to-teal-500 bg-size-200 bg-pos-0 hover:bg-pos-100 capitalize';
@endphp

<div x-data="{ disabledButton: false }" {{ $attributes->has('spinner') ? 'wire:loading.remove' : '' }}>
    @if ($attributes->has('link') || $attributes->has('link-reload'))
        <a
            {{ $attributes->merge([
                'type' => 'submit',
                'class' => $classes,
                'wire:navigate.hover' => $attributes->has('link'), // Add wire:navigate.hover if has link
            ]) }}>
            {{ __($label) }}
        </a>
    @else
        <button :disabled="disabledButton" x-on:disabling-button.window="disabledButton = $event.detail.params"
            {{ $attributes->merge([
                'type' => 'submit',
                'class' => $classes,
            ]) }}
            {{ $attributes->has('useDisabledButton') ? 'wire:dirty' : '' }}>
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
