<div>
    <x-extend.modal-container closeButton>
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
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Run Migrate
                    Fresh?
                </h3>
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-white">
                        All table will be drop!!!
                    </p>
                </div>
            </div>
        </div>

        <x-extend.modal-action>
            <x-danger-button label="Sure" wire:click="migrateFresh" spinner />
            <x-secondary-button label="Cancel" wire:click="$dispatch('closeModal')" />
        </x-extend.modal-action>
    </x-extend.modal-container>
</div>
