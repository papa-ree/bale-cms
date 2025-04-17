<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
// use App\Models\SiteConfig;

new class extends Component {
    #[On('site-name-changed')]
    public function with(): array
    {
        return [
                // 'site_name' => SiteConfig::find('site_name')->value,
                // 'site_logo' => SiteConfig::find('site_logo')->value,
            ];
    }
};

?>

<div>
    {{-- <!-- Sidebar --> --}}
    <div id="application-sidebar"
        class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 left-0 bottom-0 z-[60] lg:z-[50] w-64 bg-white border-r border-gray-200 pt-7 pb-10 overflow-y-auto scrollbar-y lg:block lg:translate-x-0 lg:right-auto lg:bottom-0 dark:scrollbar-y dark:bg-gray-800 dark:border-gray-700">

        <nav class="flex flex-col flex-wrap w-full p-6 " data-hs-accordion-always-open>
            <ul class="space-y-1.5 hs-accordion-group">
                <li>
                    <a href="/dashboard" wire:navigate.hover
                        class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                        wire:current="bg-gray-100 dark:bg-gray-900">Dashboard</a>
                </li>

                <li class="hs-accordion" id="setting-accordion">
                    <div
                        class="hs-accordion-toggle cursor-pointer flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-slate-700 hs-accordion-active:bg-gray-100 hs-accordion-active:hover:bg-gray-200 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:text-slate-800 duration-200 ease-in-out dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white">

                        Setting

                        <svg class="hidden w-3 h-3 ml-auto text-gray-600 hs-accordion-active:block group-hover:text-gray-500 dark:text-gray-400"
                            width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>

                        <svg class="block w-3 h-3 ml-auto text-gray-600 hs-accordion-active:hidden group-hover:text-gray-500 dark:text-gray-400"
                            width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                    </div>

                    <div id="setting-accordion"
                        class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                        <ul class="pt-2 pl-2">
                            <li>
                                <a href="/dashboard" wire:navigate.hover
                                    class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                    wire:current="bg-gray-100 dark:bg-gray-900">Roles</a>
                                <a href="/dashboard" wire:navigate.hover
                                    class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                    wire:current="bg-gray-100 dark:bg-gray-900">Permissions</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>

    </div>
    {{-- <!-- End Sidebar --> --}}
</div>
