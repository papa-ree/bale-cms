<?php

use function Livewire\Volt\{title, mount};
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});

title('Deleted User List');

?>

<div>

    <x-bale.page-header title="Deleted User Lists">
        <x-slot name="action">
            <x-bale.secondary-button label="User Lists" type="button" link href="{{ route('user-lists.index') }}" />
        </x-slot>
    </x-bale.page-header>

    <livewire:shared-components.pages.user-list.section.deleted-user-list-table />

    <x-bale.modal modalId="userDeleteModal" size="xl" staticBackdrop>
        <livewire:shared-components.pages.user-list.modal.user-delete-confirmation-modal />
    </x-bale.modal>
</div>
