<?php

use function Livewire\Volt\{computed, mount, usesPagination, state, uses, updating, hydrate, on};
use Spatie\Permission\Models\Permission;
use Livewire\WithoutUrlPagination;

uses([WithoutUrlPagination::class]);

usesPagination();

state(['query']);

updating([
    'query' => fn() => $this->resetPage(),
]);

hydrate(fn() => $this->dispatch('paginated'));

$availablePermissions = computed(function () {
    $searchTerm = htmlspecialchars($this->query, ENT_QUOTES, 'UTF-8');

    return Permission::where('name', 'like', '%' . $searchTerm . '%')
        ->orderBy('name')
        ->paginate(10);
});

on([
    'refresh-permission-list' => function () {
        return $this->availablePermissions;
    },
]);

$getRoleColor = computed(function ($index) {
    $colors = ['bg-emerald-100 text-emerald-600', 'bg-cyan-100 text-cyan-600', 'bg-purple-100 text-purple-600', 'bg-pink-100 text-pink-600', 'bg-teal-100 text-teal-600', 'bg-sky-100 text-sky-600'];
    return $colors[$index % count($colors)];
});

?>

<div>

    <x-bale.table :links="$this->availablePermissions" header>

        <x-slot name="thead">
            <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Permission Name') }}
                        </span>
                    </div>
                </th>
                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 lg:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            {{ __('Assignment to') }}
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Created At
                        </span>
                    </div>
                </th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($this->availablePermissions as $permission)
                <tr wire:key='permission-{{ $permission->id }}' class="hover:bg-gray-50 dark:hover:bg-slate-700/50"
                    x-data="{
                        openPermissionModal() {
                                $wire.dispatch('openBaleModal', { id: 'permissionModal' });
                                this.$dispatch('permission-data', {
                                    modalTitle: 'Edit Permission',
                                    permissionId: {{ $permission->id }},
                                    permissionName: '{{ $permission->name }}',
                                    editMode: true
                                });
                            },
                            openPermissionDeleteModal() {
                                $wire.dispatch('openBaleModal', { id: 'permissionDeleteModal' });
                                this.$dispatch('permission-data', {
                                    permissionId: {{ $permission->id }},
                                    permissionName: '{{ $permission->name }}',
                                });
                            }
                    }">
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 max-w-0 sm:w-auto sm:max-w-none">
                        <div @click="openPermissionModal"
                            class="block text-sm text-gray-800 cursor-pointer dark:text-gray-200">
                            {{ $permission->name }}
                        </div>
                        <dl class="font-normal lg:hidden">
                            <dt class="sr-only">Page Slug</dt>
                            <dd class="mt-1 text-gray-700 truncate">
                                <span class="block text-xs text-gray-500 dark:text-gray-200">
                                    @foreach ($permission->roles as $role)
                                        <div @click="openPermissionModal"
                                            class="inline-block px-2 py-1 truncate cursor-pointer text-xs rounded-full {{ $this->getRoleColor($loop->index) }}">
                                            {{ $role->name }}
                                        </div>
                                    @endforeach
                                </span>
                            </dd>
                            <dt class="sr-only sm:hidden">Created At</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">Created At
                                    {{ $permission->created_at }}</span>
                            </dd>
                        </dl>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        @foreach ($permission->roles as $role)
                            <span
                                class="inline-block truncate px-2 py-1 text-xs rounded-full {{ $this->getRoleColor($loop->index) }}">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 sm:table-cell">
                        <span
                            class="block text-sm text-gray-500">{{ date_format($permission->created_at, 'd-M-Y') }}</span>
                    </td>

                    <td class="py-4 pl-3 pr-4 text-sm font-medium text-right ">
                        <div class="hs-dropdown relative inline-block [--placement:bottom|left]">
                            <button id="hs-table-dropdown-{{ $permission->name }}" type="button"
                                class="hs-dropdown-toggle py-1.5 px-2 inline-flex justify-center items-center gap-2 rounded-lg text-gray-700 align-middle disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-emerald-300 transition-all text-sm dark:text-neutral-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
                                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="19" cy="12" r="1" />
                                    <circle cx="5" cy="12" r="1" />
                                </svg>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden divide-y divide-gray-200 min-w-40 z-10 bg-white shadow-2xl rounded-lg p-2 mt-2 dark:divide-neutral-700 dark:bg-neutral-800 dark:border dark:border-neutral-700"
                                aria-labelledby="hs-table-dropdown-{{ $permission->name }}">
                                <div class="py-2 first:pt-0 last:pb-0">

                                    <button x-data
                                        class="flex items-center w-full px-3 py-2 text-sm text-gray-800 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-emerald-500 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300"
                                        @click="openPermissionModal">
                                        Edit
                                    </button>

                                </div>

                                <div class="py-2 first:pt-0 last:pb-0">
                                    <button @click="openPermissionDeleteModal"
                                        class="flex items-center w-full px-3 py-2 text-sm text-red-600 rounded-lg gap-x-3 hover:bg-gray-100 focus:ring-2 focus:ring-emerald-500 dark:text-red-500 dark:hover:bg-neutral-700">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>

    </x-bale.table>
</div>
