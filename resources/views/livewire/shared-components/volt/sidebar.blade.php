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

        @persist('sidebar')
        <nav class="flex flex-col flex-wrap w-full p-6 " data-hs-accordion-always-open>
            <ul class="space-y-1.5 hs-accordion-group">
                <li>
                    <a href="/dashboard" wire:navigate.hover
                        class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                        wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Dashboard') }}</a>
                </li>

                @if (
                        auth()->user()->can('permission management') &&
                        auth()->user()->can('role management') &&
                        auth()->user()->can('user management')
                    )
                    <li class="hs-accordion" id="setting-accordion">
                        <div
                            class="hs-accordion-toggle cursor-pointer flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-slate-700 hs-accordion-active:bg-gray-100 hs-accordion-active:hover:bg-gray-200 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:text-slate-800 duration-200 ease-in-out dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white">

                            {{ __('User Management') }}

                            <svg class="hidden w-3 h-3 ml-auto text-gray-600 hs-accordion-active:block group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>

                            <svg class="block w-3 h-3 ml-auto text-gray-600 hs-accordion-active:hidden group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div id="setting-accordion"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="pt-2 pl-2">
                                @can('user management')
                                    <li>
                                        <a href="/user-lists" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Users') }}</a>
                                    </li>
                                @endcan

                                @can('role management')
                                    <li>
                                        <a href="/roles" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Roles') }}</a>
                                    </li>
                                @endcan

                                @can('permission management')
                                    <li>
                                        <a href="/permissions" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Permissions') }}</a>
                                    </li>
                                @endcan

                            </ul>
                        </div>
                    </li>
                @endif

                @can('domain read')
                    <li class="hs-accordion" id="domain-accordion">
                        <div
                            class="hs-accordion-toggle cursor-pointer flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-slate-700 hs-accordion-active:bg-gray-100 hs-accordion-active:hover:bg-gray-200 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:text-slate-800 duration-200 ease-in-out dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white">

                            {{ __('Domains') }}

                            <svg class="hidden w-3 h-3 ml-auto text-gray-600 hs-accordion-active:block group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>

                            <svg class="block w-3 h-3 ml-auto text-gray-600 hs-accordion-active:hidden group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div id="domain-accordion"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="pt-2 pl-2">
                                <li>
                                    <a href="/dns" wire:navigate.hover
                                        class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                        wire:current="bg-gray-100 dark:bg-gray-900">{{ __('DNS Records') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('network read')
                    <li class="hs-accordion" id="network-accordion">
                        <div
                            class="hs-accordion-toggle cursor-pointer flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-slate-700 hs-accordion-active:bg-gray-100 hs-accordion-active:hover:bg-gray-200 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:text-slate-800 duration-200 ease-in-out dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white">

                            {{ __('Network') }}

                            <svg class="hidden w-3 h-3 ml-auto text-gray-600 hs-accordion-active:block group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>

                            <svg class="block w-3 h-3 ml-auto text-gray-600 hs-accordion-active:hidden group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div id="network-accordion"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="pt-2 pl-2">
                                <li>
                                    <a href="/network/ip-publics" wire:navigate.hover
                                        class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                        wire:current="bg-gray-100 dark:bg-gray-900">{{ __('IP Public') }}</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcan

                @can('contact read')
                    <li>
                        <a href="/contacts" wire:navigate.hover
                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Contact') }}</a>
                    </li>
                @endcan

                @can('token read')
                    <li>
                        <a href="/tokens" wire:navigate.hover
                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Token') }}</a>
                    </li>
                @endcan


                @can('inventory read')
                    <li class="hs-accordion" id="inv-accordion">
                        <div
                            class="hs-accordion-toggle cursor-pointer flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-slate-700 hs-accordion-active:bg-gray-100 hs-accordion-active:hover:bg-gray-200 text-sm text-slate-700 rounded-md hover:bg-gray-100 hover:text-slate-800 duration-200 ease-in-out dark:bg-gray-800 dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white">

                            {{ __('iNV') }}

                            <svg class="hidden w-3 h-3 ml-auto text-gray-600 hs-accordion-active:block group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 11L8.16086 5.31305C8.35239 5.13625 8.64761 5.13625 8.83914 5.31305L15 11"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>

                            <svg class="block w-3 h-3 ml-auto text-gray-600 hs-accordion-active:hidden group-hover:text-gray-500 dark:text-gray-400"
                                width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 5L8.16086 10.6869C8.35239 10.8637 8.64761 10.8637 8.83914 10.6869L15 5"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                            </svg>
                        </div>

                        <div id="inv-accordion"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 hidden">
                            <ul class="pt-2 pl-2">

                                @can('inventory overview')
                                    <li>
                                        <a href="/inventory-overviews" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Overview') }}</a>
                                    </li>
                                @endcan

                                @can('inventory master item read')
                                    <li>
                                        <a href="/master-items" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Master Item') }}</a>
                                    </li>
                                @endcan

                                @can('inventory read')
                                    <li>
                                        <a href="inventories" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Stock Item') }}</a>
                                    </li>
                                @endcan

                                @can('inventory movement read')
                                    <li>
                                        <a href="/inventory-movements" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Item Movement') }}</a>
                                    </li>
                                @endcan

                                @can('inventory assignment read')
                                    <li>
                                        <a href="/it-inventories" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('IT Inventory') }}</a>
                                    </li>
                                @endcan

                                @can('inventory device type read')
                                    <li>
                                        <a href="/device-types" wire:navigate.hover
                                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                                            wire:current="bg-gray-100 dark:bg-gray-900">{{ __('Device Type') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </div>
                    </li>
                @endcan

            </ul>
        </nav>
        @endpersist

    </div>
    {{-- <!-- End Sidebar --> --}}
</div>