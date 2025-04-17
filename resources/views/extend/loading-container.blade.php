@props(['target' => null])
<div {{ $attributes->merge(['type' => 'submit', 'class' => 'flex items-center']) }}>
    <div class="animate-spin inline-block w-5 h-5 border-[2px] border-current border-t-transparent text-emerald-400 rounded-full me-3"
        role="status" aria-label="loading" wire:loading wire:target='{{ $target }}'>
        <span class="sr-only">Loading...</span>
    </div>
    {{ $slot }}
</div>
