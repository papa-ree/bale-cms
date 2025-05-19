<?php

use Livewire\Volt\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Models\user;
use Illuminate\Support\Facades\DB;

new class extends Component {
    #[Locked]
    public $user_id;

    #[Locked]
    public $user_name;

    public $force_delete = false;

    public function deleteUser(LivewireAlert $alert, $user_id)
    {
        $this->authorize('user management');

        $this->user_id = $user_id;

        $user = User::whereUuid($this->user_id)->withTrashed()->first();
        $dbName = $user->tenant->database ?? null;

        DB::beginTransaction();

        try {
            if ($this->force_delete) {
                $user->forceDelete();
            } else {
                $user->delete();
            }

            DB::commit();

            // Jika force delete dan ada database tenant, panggil method drop terpisah
            if ($this->force_delete && $dbName) {
                $this->dropTenantDatabase($dbName);
            }

            $this->dispatch('closeBaleModal', id: 'userDeleteModal');
            $this->dispatch('refresh-user-list');

            $alert->title('User Deleted!')->position('top-end')->success()->toast()->show();
        } catch (\Throwable $th) {
            DB::rollBack();
            info($th->getMessage());

            $this->dispatch('message-failed');
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    private function dropTenantDatabase(string $dbName): void
    {
        DB::statement("DROP DATABASE `$dbName`");
    }
};
?>

<div x-data="{
    userId: '',
    userName: '',
    init() {
        this.resetState();
    },
    resetState() {
        this.userId = '';
        this.userName = '';
        this.forceDelete = false;
    },
    handleUserData(detail) {
        this.userId = detail.userId;
        this.userName = detail.userName;
        this.forceDelete = detail.forceDelete;
        $wire.set('force_delete', detail.forceDelete);
    },
}" x-init="init()" @user-data.window="handleUserData($event.detail)">
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
                Delete <span x-text="userName"></span> user?
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-white">
                    Are you sure you want to delete
                    this item? All
                    of your data will be permanently removed
                    from our servers forever. This action cannot be undone.
                </p>
                <template x-if="forceDelete">
                    <ul class="mt-3 space-y-3">
                        <li class="flex items-center gap-x-3">
                            <span
                                class="flex items-center justify-center text-red-500 rounded-full size-5 bg-red-50 dark:bg-red-800/30 dark:text-red-500">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>
                            <span class="text-sm text-gray-600 dark:text-white">
                                Tenant User will be deleted
                            </span>
                        </li>
                        <li class="flex items-center gap-x-3">
                            <span
                                class="flex items-center justify-center text-red-500 rounded-full size-5 bg-red-50 dark:bg-red-800/30 dark:text-red-500">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>
                            <span class="text-sm text-gray-600 dark:text-white">
                                Tenant Database will be deleted
                            </span>
                        </li>
                    </ul>
                </template>
            </div>
            <template x-if="!forceDelete">
                <div class="flex my-4 sm:my-6">
                    <input type="checkbox" wire:model='force_delete'
                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-emerald-600 focus:ring-emerald-500 checked:border-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-gray-800"
                        id="bale-change-password-checkbox">
                    <label for="bale-change-password-checkbox" class="ms-3">
                        <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-300">Force
                            Delete</span>
                        <span id="bale-change-password-checkbox"
                            class="block text-sm text-gray-600 dark:text-neutral-500">{{ __('Tenant database will be deleted') }}</span>
                    </label>
                </div>
            </template>
        </div>
    </div>

    <x-bale.modal-action>
        <x-bale.secondary-button label="Cancel" wire:click="$dispatch('closeBaleModal', { id: 'userDeleteModal' })"
            class="ml-3" />
        <x-bale.danger-button label="Gaskeun!" @click="$wire.deleteUser(userId); useSpinner()" />
    </x-bale.modal-action>
</div>
