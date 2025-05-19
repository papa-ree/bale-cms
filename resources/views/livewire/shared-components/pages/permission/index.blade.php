<?php

use function Livewire\Volt\{title, mount};
use Spatie\Permission\Models\Permission;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});

title('Permissions');

?>

<div>

    <x-bale.page-header title="Permissions">
        <x-slot name="action">
            <x-bale.button label="Create Permission" type="button"
                @click="$dispatch('openBaleModal', { id: 'permissionModal' }); $dispatch('modal-reset')" />
        </x-slot>
    </x-bale.page-header>

    <livewire:shared-components.pages.permission.section.permission-table />

    <x-bale.modal modalId="permissionModal" size="sm" staticBackdrop>
        <livewire:shared-components.pages.permission.modal.permission-cru-modal />
    </x-bale.modal>

    <x-bale.modal modalId="permissionDeleteModal" size="xl" staticBackdrop>
        <livewire:shared-components.pages.permission.modal.permission-delete-confirmation-modal />
    </x-bale.modal>
</div>
