<?php

use function Livewire\Volt\{title, mount};
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});

title('User List');

?>

<div>

    <x-bale.page-header title="User Lists">
        <x-slot name="action">
            <div class="inline-block [--placement:left] hs-tooltip group">
                <a href="{{ route('user-lists.deleted') }}" wire:navigate.hover
                    class="flex items-center justify-center p-3 mr-3 text-sm font-medium transition-all bg-gray-200 border border-gray-300 rounded-lg hs-tooltip-toggle shrink-0 dark:text-white hover:bg-gray-300 focus:outline-hidden focus:bg-gray-300 disabled:opacity-50 disabled:pointer-events-none">
                    <i data-lucide="trash-2" class="w-5 h-5 text-gray-500 group-hover:text-gray-800"></i>
                    <span
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity bg-gray-900 rounded-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible shadow-2xs dark:bg-neutral-700"
                        role="tooltip">
                        {{ __('Deleted User') }}
                    </span>
                </a>
            </div>
            <x-bale.button label="Create User" type="button" link href="{{ route('user-lists.create', 'new') }}" />
        </x-slot>
    </x-bale.page-header>

    <livewire:shared-components.pages.user-list.section.user-list-table />

    <x-bale.modal modalId="userDeleteModal" size="xl" staticBackdrop>
        <livewire:shared-components.pages.user-list.modal.user-delete-confirmation-modal />
    </x-bale.modal>
</div>
