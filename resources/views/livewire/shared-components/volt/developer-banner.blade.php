<?php

use Livewire\Volt\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Config;

new class extends Component {
    public $development;

    public function mount()
    {
        $this->development = Config::get(['admin.development']);
    }

    public function optimizeClear(LivewireAlert $alert)
    {
        try {
            $this->authorize('optimize clear');
            Artisan::call('optimize:clear');
            $alert->title('Optimized!')->toast()->position('top-end')->success()->show();
        } catch (\Throwable $th) {
            $alert->title('Something Wrong!')->toast()->position('top-end')->error()->show();
        }
    }

    #[Computed]
    public function availableMenus()
    {
        $menus = [
            [
                'menu_name' => 'log',
                'menu_icon' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>',
                'menu_url' => 'log-viewer',
                'show' => $this->development['admin.development'],
            ],
        ];

        return $menus;
    }
};

?>

<div>
    <div
        class="fixed bottom-0 right-0 hidden transition-all duration-500 rounded-tl-lg md:block sm:rounded-none lg:pl-72 sm:left-0 bg-gradient-to-tl from-rose-600 via-red-500 to-orange-400 bg-size-200 bg-pos-0 hover:bg-pos-100">
        <div class="px-4 py-1.5 sm:px-6 lg:px-8 mx-auto">
            <!-- Grid -->
            <div class="grid justify-center gap-2 md:grid-cols-2 md:justify-between md:items-center">

                <div class="text-center md:text-start md:order-2 md:flex md:justify-end md:items-center">

                    @foreach ($this->availableMenus as $menu)
                        @if ($menu['show'])
                            <span class="inline-block border-e border-white/[.3] w-px h-5 mx-2"></span>
                            <a href="{{ $menu['menu_url'] }}" wire:navigate
                                class="py-1 cursor-pointer px-3 inline-flex justify-center items-center gap-2 rounded-lg font-medium text-white hover:bg-white/[.1] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all text-sm">
                                {!! $menu['menu_icon'] !!}
                                {{ $menu['menu_name'] }}
                            </a>
                        @endif
                    @endforeach

                    <span class="inline-block border-e border-white/[.3] w-px h-5 mx-2"></span>
                    <div class="relative">
                        <div class="py-1 cursor-pointer px-3 inline-flex justify-center items-center gap-2 rounded-lg font-medium text-white hover:bg-white/[.1] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all text-sm"
                            wire:click='optimizeClear'>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Optimize
                        </div>

                        <div wire:loading wire:target='optimizeClear'
                            class="absolute top-0 start-0 w-full h-full bg-white/[.5] rounded-lg dark:bg-gray-800/[.4]">
                        </div>

                        <div wire:loading wire:target='optimizeClear'
                            class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 start-1/2">
                            <div class="animate-spin inline-block w-3 h-3 border-[2px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500"
                                role="status" aria-label="loading">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>

                    @if ($development['admin.development'])
                        <span class="inline-block border-e border-white/[.3] w-px h-5 mx-2"></span>
                        <div class="relative">
                            <div wire:click="$dispatch('openModal', { component: 'shared-components.modal.migrate-fresh-confirmation-modal' })"
                                class="py-1 px-3 cursor-pointer inline-flex justify-center items-center gap-2 rounded-lg font-medium text-white hover:bg-white/[.1] focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                </svg>
                                Migrate Fresh
                            </div>

                            <div wire:loading wire:target='migrateFresh'
                                class="absolute top-0 start-0 w-full h-full bg-white/[.5] rounded-lg dark:bg-gray-800/[.4]">
                            </div>

                            <div wire:loading wire:target='migrateFresh'
                                class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 start-1/2">
                                <div class="animate-spin inline-block w-3 h-3 border-[2px] border-current border-t-transparent text-blue-600 rounded-full dark:text-blue-500"
                                    role="status" aria-label="loading">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="items-center hidden md:flex md:order-1">
                    <p class="inline-block text-sm font-semibold text-white me-5">
                        Artisan Call
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="hs-dropdown [--placement:top-left] inline-flex md:hidden bottom-1 right-1 fixed">
        <button id="bale-dev-tool-dropup" type="button"
            class="flex items-center p-3 text-sm tracking-wide text-center text-white transition-all duration-500 hs-dropdown-toggle rounded-xl bg-gradient-to-tl from-rose-600 via-red-500 to-orange-400 bg-size-200 bg-pos-0 hover:bg-pos-100""
            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                class="transition duration-300 hs-dropdown-open:-rotate-90 size-4">
                <path fill-rule="evenodd"
                    d="M12 6.75a5.25 5.25 0 0 1 6.775-5.025.75.75 0 0 1 .313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 0 1 1.248.313 5.25 5.25 0 0 1-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 1 1 2.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0 1 12 6.75ZM4.117 19.125a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z"
                    clip-rule="evenodd" />
                <path
                    d="m10.076 8.64-2.201-2.2V4.874a.75.75 0 0 0-.364-.643l-3.75-2.25a.75.75 0 0 0-.916.113l-.75.75a.75.75 0 0 0-.113.916l2.25 3.75a.75.75 0 0 0 .643.364h1.564l2.062 2.062 1.575-1.297Z" />
                <path fill-rule="evenodd"
                    d="m12.556 17.329 4.183 4.182a3.375 3.375 0 0 0 4.773-4.773l-3.306-3.305a6.803 6.803 0 0 1-1.53.043c-.394-.034-.682-.006-.867.042a.589.589 0 0 0-.167.063l-3.086 3.748Zm3.414-1.36a.75.75 0 0 1 1.06 0l1.875 1.876a.75.75 0 1 1-1.06 1.06L15.97 17.03a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>

        </button>

        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-1 space-y-0.5 mt-2 divide-y divide-gray-200 dark:bg-neutral-800 border-red-400 border-2 dark:border-red-700 dark:divide-neutral-700"
            role="menu" aria-orientation="vertical" aria-labelledby="bale-dev-tool-dropup">
            <div class="py-2 first:pt-0 last:pb-0">
                <span class="block px-3 py-2 text-xs font-medium text-red-400 uppercase dark:text-red-500">
                    Artisan Call
                </span>

                @foreach ($this->availableMenus as $menu)
                    <a href="{{ $menu['menu_url'] }}" wire:navigate
                        class="flex w-full items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                        {!! $menu['menu_icon'] !!}
                        {{ $menu['menu_name'] }}
                    </a>
                @endforeach

                <div wire:click='optimizeClear'
                    class="flex w-full cursor-pointer items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                        class="shrink-0 size-4">
                        <path fill-rule="evenodd"
                            d="M13.836 2.477a.75.75 0 0 1 .75.75v3.182a.75.75 0 0 1-.75.75h-3.182a.75.75 0 0 1 0-1.5h1.37l-.84-.841a4.5 4.5 0 0 0-7.08.932.75.75 0 0 1-1.3-.75 6 6 0 0 1 9.44-1.242l.842.84V3.227a.75.75 0 0 1 .75-.75Zm-.911 7.5A.75.75 0 0 1 13.199 11a6 6 0 0 1-9.44 1.241l-.84-.84v1.371a.75.75 0 0 1-1.5 0V9.591a.75.75 0 0 1 .75-.75H5.35a.75.75 0 0 1 0 1.5H3.98l.841.841a4.5 4.5 0 0 0 7.08-.932.75.75 0 0 1 1.025-.273Z"
                            clip-rule="evenodd" />
                    </svg>
                    Optimize Clear
                </div>

                @if ($development['admin.development'])
                    <div wire:click="$dispatch('openModal', { component: 'shared-components.modal.migrate-fresh-confirmation-modal' })"
                        class="flex w-full cursor-pointer items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                            class="shrink-0 size-4">
                            <path d="M8 7c3.314 0 6-1.343 6-3s-2.686-3-6-3-6 1.343-6 3 2.686 3 6 3Z" />
                            <path
                                d="M8 8.5c1.84 0 3.579-.37 4.914-1.037A6.33 6.33 0 0 0 14 6.78V8c0 1.657-2.686 3-6 3S2 9.657 2 8V6.78c.346.273.72.5 1.087.683C4.42 8.131 6.16 8.5 8 8.5Z" />
                            <path
                                d="M8 12.5c1.84 0 3.579-.37 4.914-1.037.366-.183.74-.41 1.086-.684V12c0 1.657-2.686 3-6 3s-6-1.343-6-3v-1.22c.346.273.72.5 1.087.683C4.42 12.131 6.16 12.5 8 12.5Z" />
                        </svg>
                        Migrate Fresh
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
