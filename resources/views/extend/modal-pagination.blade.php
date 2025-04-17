<div>
    @if ($paginator->hasPages())
        <div class="space-y-6">
            <nav role="navigation" class="flex items-center justify-center mx-auto gap-x-6"
                aria-label="Pagination Navigation">
                <span>
                    @if ($paginator->onFirstPage())
                        <button type="button"
                            class="flex flex-shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-semibold focus:outline-none rounded-xl bg-gray-600 cursor-auto text-white disabled:opacity-50 disabled:pointer-events-none"
                            disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                    @else
                        <button type="button" wire:click="previousPage" wire:loading.attr="disabled" rel="prev"
                            class="flex flex-shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-semibold focus:outline-none rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                    @endif
                </span>
                <span>
                    @if ($paginator->onLastPage())
                        <button type="button"
                            class="flex flex-shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-semibold focus:outline-none rounded-xl bg-gray-600 cursor-auto text-white disabled:opacity-50 disabled:pointer-events-none"
                            disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    @else
                        <button type="button" wire:click="nextPage" wire:loading.attr="disabled" rel="next"
                            class="flex flex-shrink-0 justify-center items-center gap-2 size-[38px] text-sm font-semibold focus:outline-none rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    @endif
                </span>
            </nav>
            <div class="flex items-center justify-center">
                <p class="text-sm leading-5 text-gray-700">
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    <span>{!! __('of') !!}</span>
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    <span>{!! __('announcement') !!}</span>
                </p>
            </div>
        </div>
    @endif
</div>
