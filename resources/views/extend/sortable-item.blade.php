@props([
    'nested' => false,
    'itemId' => null,
    'route' => null,
    'itemName',
    'children' => [],
    // 'childrenRoute' => null,
])

<div @if ($nested) class="nested-2"
    wire:sortable-group.item="{{ $itemId }}"
    @else
    class="nested-1"
    wire:sortable.item="{{ $itemId }}" @endif
    wire:key="sortable-item-{{ $itemId }}">
    <div
        class="flex items-center px-3 py-4 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-lg gap-x-3 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-200">

        <div class="flex items-center gap-x-3"
            @if ($nested) wire:sortable-group.handle
@else
         wire:sortable.handle @endif>
            <svg class="text-gray-500 size-5 cursor-grab dark:text-neutral-500" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="12" r="1"></circle>
                <circle cx="9" cy="5" r="1"></circle>
                <circle cx="9" cy="19" r="1"></circle>
                <circle cx="15" cy="12" r="1"></circle>
                <circle cx="15" cy="5" r="1"></circle>
                <circle cx="15" cy="19" r="1"></circle>
            </svg>
            <a href="{{ $route }}" x-ref="{{ $itemName }}" wire:navigate.hover class="sr-only">
                {{ $itemName }}
            </a>
            <div class="transition duration-300 ease-in-out cursor-pointer hover:text-emerald-500"
                @click="$refs.{{ $itemName }}.click()">
                {{ $itemName }}
            </div>
        </div>

        <div class="hidden mr-1 shrink-0 ms-auto lg:block">
            <div class="hs-dropdown [--placement:bottom-right] [--auto-close:inside] relative inline-block">
                <button id="bale-sortable-item-dropdown-list-{{ $itemId }}" type="button"
                    class="inline-flex items-center justify-center gap-2 text-sm text-gray-700 align-middle transition-all rounded-lg hs-dropdown-toggle disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                    <svg class="flex-shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="1" />
                        <circle cx="19" cy="12" r="1" />
                        <circle cx="5" cy="12" r="1" />
                    </svg>
                </button>
                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-20 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                    aria-labelledby="bale-sortable-item-dropdown-list-{{ $itemId }}">
                    <div class="py-2 first:pt-0 last:pb-0">
                        <span class="block px-3 py-2 text-xs font-medium text-gray-400 uppercase dark:text-neutral-600">
                            Actions
                        </span>
                        <a href="{{ $route }}" wire:navigate
                            class="flex items-center px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-blue-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- children masih statis, diharapkan nanti bisa dinamis --}}
    @if (!empty($children))
        {{-- @dump($children) --}}
        <div class="mt-1 space-y-1 ps-5 nested-sortable" wire:sortable-group.item-group="{{ $itemId }}"
            wire:sortable-group.options="{ animation: 100 }">
            @foreach ($children as $child)
                <x-extend.sortable-item :itemId="$child['id']" :itemName="$child['navigation_name']" :route="route('navigation.edit', $child['id'])" :children="$child['children'] ?? []"
                    nested />
            @endforeach
        </div>
    @endif
</div>
