@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'flex capitalize items-center gap-x-3.5 py-2 px-2.5 bg-gray-100 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:dark:bg-gray-900 dark:text-white'
            : 'flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:dark:bg-gray-900 dark:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if (config('bale-cms.spa')) wire:navigate @endif>
    {{ $slot }}
</a>
