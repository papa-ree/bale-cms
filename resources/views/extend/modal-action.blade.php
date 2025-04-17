<div class="flex flex-row-reverse mt-5 sm:mt-4 gap-x-3">
    {{ $slot }}
    @if ($attributes->has('closeButton'))
        <x-secondary-button type="button" label="close" wire:click="$dispatch('closeModal')" />
    @endif
</div>
