<?php

use Livewire\Volt\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

new class extends Component {
    public $role;
    public $roleCount;
    public $rolePermissions;
    public $permissionCount;
    public $permissionAmmount = 5;

    public function mount(Role $role)
    {
        $this->role = $role;

        $this->roleCount = User::with('roles')->get()->filter(fn($user) => $user->roles->where('name', $role->name)->toArray())->count();

        $getPermission = $role->permissions()->orderBy('name')->pluck('name');

        $this->rolePermissions = $getPermission->take($this->permissionAmmount);

        $this->permissionCount = $getPermission->count() - $this->permissionAmmount;
    }
};
?>

<div>
    <div class="h-full p-4 bg-white border border-gray-200 dark:bg-gray-800 rounded-xl sm:p-6 xl:p-8">

        <div class="mb-4 space-y-3">
            <div class="flex items-center">
                <h2 class="text-lg antialiased font-bold text-gray-800 capitalize dark:text-gray-200">
                    {{ $role->name }}
                </h2>
            </div>
            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">
                Total users: {{ $roleCount }}
            </p>
        </div>

        <ul class="space-y-3 text-sm text-gray-600 list-disc marker:text-blue-600 ps-6 dark:text-neutral-400">
            @foreach ($rolePermissions as $permission)
                <li>{{ $permission }}</li>
            @endforeach
            @if ($permissionCount > 0)
                <li>{{ $permissionCount }} more...</li>
            @endif
        </ul>

        {{-- Action Button --}}
        <div class="flex items-center justify-end gap-x-3">
            <x-bale.danger-button label="Delete" wire:click="$dispatch('openBaleModal', { id: '{{ $role->id }}' })"
                spinner />
            <x-bale.secondary-button href="{{ route('roles.edit', $role->id) }}" link label="Edit" />
        </div>

    </div>

    <x-bale.modal modalId="{{ $role->id }}" size="xl" staticBackdrop>
        <livewire:shared-components.pages.role.modal.role-delete-confirmation-modal :role="$role->id" />
    </x-bale.modal>

</div>
