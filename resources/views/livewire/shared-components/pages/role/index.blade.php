<?php

use function Livewire\Volt\{layout, state, title, mount, computed, on};
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

mount(function () {
    if (session()->has('saved')) {
        LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
    }
});

state(['availableRoles' => fn() => Role::get('id')]);
on([
    'refresh-role-list' => function () {
        $this->availableRoles;
    },
]);

title('Roles');

?>

<div class="grid grid-cols-1 gap-8 p-4 lg:grid-cols-3 md:grid-cols-2 sm:p-6">

    @foreach ($availableRoles as $key => $role)
        <livewire:shared-components.pages.role.section.role-card wire:key='{{ $key }}' :role="$role->id" />
    @endforeach

    <a href="{{ route('roles.create') }}" wire:navigate
        class="group duration-300 border-2 border-dashed border-gray-400 min-h-[23rem] rounded-xl hover:shadow-xl shadow-inner transition-shadow">
        <div class="flex flex-col items-center justify-center h-full gap-8">
            <div
                class="p-4 transition-transform duration-300 bg-white rounded-full dark:bg-gray-800 group-hover:scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-shield-plus">
                    <path
                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                    <path d="M9 12h6" />
                    <path d="M12 9v6" />
                </svg>
            </div>
            {{ __('Create Role') }}
        </div>
    </a>

</div>
