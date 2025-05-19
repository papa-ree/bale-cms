<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Validate;
use Paparee\BaleCms\App\Traits\WithGlobalValidationHandler;
use Paparee\BaleCms\App\Rules\LatinAndSpace;

new class extends Component {
    use WithGlobalValidationHandler;

    #[Locked]
    public $permission_id;

    #[Validate(['required', 'string', 'max:100', 'unique:permissions,name', new LatinAndSpace()])]
    public $permission_name;

    public $edit_mode = false;

    #[On('permissionData')]
    public function getPermissionData(Permission $permission)
    {
        // dump($permission);
        $this->permission_id = $permission->id;
        $this->permission_name = $permission->name;
    }

    public function store($data, LivewireAlert $alert)
    {
        $this->permission_id = $data['permission_id'];
        $this->permission_name = $data['permission_name'];
        $this->edit_mode = $data['edit_mode'];

        $this->validate();

        $this->dispatch('disabling-button', params: true);

        DB::beginTransaction();
        try {
            Permission::create([
                'name' => $this->permission_name,
                'guard_name' => 'web',
            ]);

            DB::commit();

            $this->dispatch('closeBaleModal', id: 'permissionModal');
            $this->dispatch('refresh-permission-list');

            $alert->title('Permission Created!')->position('top-end')->success()->toast()->show();
            $this->dispatch('disabling-button', params: false);
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());

            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    private function update($date, LivewireAlert $alert)
    {
        $this->permission_id = $data['permission_id'];
        $this->permission_name = $data['permission_name'];
        $this->edit_mode = $data['edit_mode'];

        $this->validate();

        $this->dispatch('disabling-button', params: true);

        DB::beginTransaction();

        try {
            $permission = Permission::find($this->permission_id);
            $permission->name = $this->permission_name;
            $permission->guard = 'web';
            $permission->save();

            DB::commit();

            $this->dispatch('closeBaleModal', id: 'permissionModal');
            $this->dispatch('refresh-permission-list');

            $alert->title('Permission Updated!')->position('top-end')->success()->toast()->show();
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());

            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    public function resetVal()
    {
        $this->reset();
        $this->resetValidation();
    }
};
?>

<div>

    <div x-data="{
        modalTitle: 'Create Permission',
        permissionId: '',
        permissionName: '',
        editMode: false,
        open: false,
        init() {
            this.resetState();
        },
        resetState() {
            this.modalTitle = 'Create Permission';
            this.permissionId = '';
            this.permissionName = '';
            this.editMode = false;
        },
        handlePermissionData(detail) {
            this.modalTitle = detail.modalTitle;
            this.permissionId = detail.permissionId;
            this.permissionName = detail.permissionName;
            this.editMode = detail.editMode;
        },
    }" x-init="init()" @permission-data.window="handlePermissionData($event.detail)"
        @modal-reset.window="init()">

        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            <span x-text="modalTitle"></span>
        </h3>

        <form wire:submit='store(Object.fromEntries(new FormData($event.target)))' class="mt-8">
            <div class="mb-4" x-trap.inert="open">
                <x-bale.input type="hidden" name="permission_id" x-model="permissionId" />
                <x-bale.input type="hidden" name="edit_mode" x-model="editMode" />
                <x-bale.input type="text" label="Permission Name" name="permission_name" x-model="permissionName"
                    autofocus x-trap="open" />
                <x-input-error for="permission_name" />
            </div>
            <x-bale.modal-action>
                <x-bale.secondary-button label="Cancel" type="button" class="ml-3"
                    wire:click="$dispatch('closeBaleModal', { id: 'permissionModal' }); $wire.resetVal()" />
                <x-bale.button label="Save" type="submit" x-show="!editMode" class="ml-3"
                    wire:dirty.attr="disabled" wire:target='permission_name' @click="useSpinner()" />
                <x-bale.button label="Update" type="submit" x-show="editMode" wire:dirty.attr="disabled"
                    wire:target='permission_name' @click="useSpinner()" />
            </x-bale.modal-action>
        </form>

    </div>
</div>
