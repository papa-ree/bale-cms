<?php

namespace App\Livewire\SharedComponents\Pages\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleCru extends Component
{
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

    public function render()
    {
        View::addNamespace('bale-custom', base_path('vendor/papa-ree/bale-cms/resources/views/livewire/shared-components'));
        return view('livewire.shared-components.pages.role.role-cru')
                ->title($this->edit_mode ? 'Edit Role' : 'Create Role');
    }

    #[Computed()]
    public function permissions()
    {
        return Permission::get(['name']);
    }

    public function store(LivewireAlert $alert)
    {
        $this->validate(
            [
                'name' => ['required', 'string', 'max:10', 'unique:roles'],
                'role_permissions' => ['required', 'unique:roles,name'],
                ]
            );
            
        DB::beginTransaction();
            
        try {
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
        $this->validate(
            [
                'name' => ['required', 'string', 'max:10', Rule::unique('roles')->ignore($this->role)],
                'role_permissions.*' => 'required',
            ]
        );

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
}
