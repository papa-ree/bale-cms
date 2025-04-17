@props(['title'])
<div class="p-6 text-left transition-all transform bg-white rounded-lg dark:bg-gray-800 dark:text-white sm:p-8">

    {{-- close button --}}
    @if ($attributes->has('closeButton'))
        <div class="absolute top-0 right-0 block pt-4 pr-4">
            <button type="button" wire:click="$dispatch('closeModal')"
                class="text-gray-400 transition bg-white rounded-md focus:outline-none dark:bg-gray-800 dark:text-white hover:text-gray-500">
                <span class="sr-only">Close</span>
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <div class="absolute top-0 left-0 block pt-5 pl-6 text-lg font-semibold capitalize">
        {{ $title ?? '' }}
    </div>

    <div class="mt-8">{{ $slot }}</div>
</div>
