<?php

use Livewire\Volt\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;

new class extends Component {
    #[Locked]
    public $role;

    public function mount(Role $role)
    {
        $this->role = $role;
    }

    public function deleteRole(LivewireAlert $alert)
    {
        DB::beginTransaction();

        try {
            $this->delete();
            DB::commit();

            $this->dispatch('closeBaleModal', id: strval($this->role->id));
            $this->dispatch('refresh-role-list');

            $alert->title('Role Deleted!')->position('top-end')->success()->toast()->show();
        } catch (\Throwable $th) {
            $this->dispatch('message-failed');
            DB::rollBack();
            info($th->getMessage());

            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    private function delete()
    {
        return Role::find($this->role->id)->delete();
    }
};
?>

<div>

    <div class="sm:flex sm:items-start">
        <div
            class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">
                Delete {{ $role->name }} role?
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-white">
                    Are you sure you want to delete
                    this item? All
                    of your data will be permanently removed
                    from our servers forever. This action cannot be undone.
                </p>
            </div>
        </div>
    </div>

    <x-bale.modal-action>
        <x-bale.secondary-button label="Cancel" wire:click="$dispatch('closeBaleModal', { id: '{{ $role->id }}' })"
            class="ml-3" />
        <x-bale.danger-button label="Gaskeun!" wire:click="deleteRole" spinner />
    </x-bale.modal-action>

</div>
