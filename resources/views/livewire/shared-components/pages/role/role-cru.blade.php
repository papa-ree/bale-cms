<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component {
    public Role $role;

    public $name;

    public $role_permissions = [];

    public $edit_mode = false;

    public function mount(Role $role)
    {
        $this->edit_mode = $role->exists ? true : false;

        if ($role->exists) {
            $this->name = $role->name;
            $this->role_permissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function rendering(View $view): void
    {
        $view->title($this->edit_mode ? 'Edit Role' : 'Create Role');
    }

    #[Computed]
    public function permissions()
    {
        return Permission::get(['name']);
    }

    public function store(LivewireAlert $alert)
    {
        $this->validate([
            'name' => ['required', 'string', 'max:10', 'unique:roles'],
            'role_permissions' => ['required', 'unique:roles,name'],
        ]);

        DB::beginTransaction();

        try {
            $this->dispatch('disabling-button', params: false);

            $new_role = Role::create([
                'name' => $this->name,
                'guard_name' => 'web',
            ]);

            $new_role->syncPermissions($this->role_permissions);
            DB::commit();

            session()->flash('saved', [
                'title' => 'Role Created!',
            ]);

            $this->redirect('roles', navigate: true);
        } catch (\Throwable $th) {
            $this->dispatch('disabling-button', params: false);

            DB::rollBack();
            info($th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    public function update(LivewireAlert $alert)
    {
        $this->validate([
            'name' => ['required', 'string', 'max:10', Rule::unique('roles')->ignore($this->role)],
            'role_permissions.*' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $this->dispatch('disabling-button', params: true);

            $this->role->update([
                'name' => $this->name,
            ]);

            $this->role->syncPermissions($this->role_permissions);
            DB::commit();

            session()->flash('saved', [
                'title' => 'Changes Saved!',
            ]);

            $this->redirect('roles', navigate: true);
        } catch (\Throwable $th) {
            $this->dispatch('disabling-button', params: false);

            DB::rollBack();
            info($th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }
};
?>

<div>
    <x-bale.back-breadcrumb :href="route('roles.index')" label="roles list" />
    <x-bale.page-container>
        <form wire:submit='{{ $edit_mode ? 'update' : 'store' }}' class="p-4">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-2xl font-semibold text-gray-800 capitalize">
                    {{ $edit_mode ? __('Update Role ') . $this->name : __('Create Role') }}
                </h1>

                <div class="flex items-center gap-x-3">
                    <x-bale.secondary-button label="Cancel" link href="{{ route('roles.index') }}" type="button" />
                    <x-bale.button label="{{ $edit_mode ? 'update' : 'save' }}" type="submit" />
                </div>
            </div>

            <!-- Grid untuk Input Nama Role dan Daftar Permission -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Input Nama Role -->
                <div>
                    <div class="">
                        <x-bale.input label="Role Name" wire:model='name' />
                        <x-input-error for="name" />
                    </div>
                    <!-- Hint Section (Sederhana) -->
                    <div class="mt-8 text-sm text-gray-600">
                        <p>**Petunjuk:**</p>
                        <ul class="mt-2 list-disc list-inside">
                            <li>Masukkan nama role.</li>
                            <li>Pilih permission yang diperlukan.</li>
                            <li>Klik "Simpan" untuk menyimpan role.</li>
                        </ul>
                    </div>
                </div>

                <!-- Daftar Permission -->
                <div>
                    <h2 class="mb-4 text-xl font-semibold text-gray-800">Daftar Permission</h2>
                    <p class="mb-6 text-sm text-gray-600">Pilih permission yang akan dimiliki oleh role ini.</p>
                    <x-input-error for="role_permissions" />

                    <!-- List Permission -->
                    <div class="space-y-3">
                        @foreach ($this->permissions as $key => $permission)
                            <label for="{{ $permission->name }}" wire:key='{{ $permission->name }}'
                                class="flex items-center justify-between p-4 transition duration-200 bg-gray-50 rounded-xl hover:bg-gray-100">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-800">{{ $permission->name }}</h3>
                                </div>
                                <input id="{{ $permission->name }}" value="{{ $permission->name }}" type="checkbox"
                                    wire:model='role_permissions'
                                    class="w-5 h-5 text-blue-500 transition duration-200 form-checkbox rounded-xl">
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

        </form>
    </x-bale.page-container>
</div>
