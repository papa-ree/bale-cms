<div>
    <form wire:submit='updatePassword' x-cloak autocomplete="off">

        <div class="mb-4 sm:mb-6">
            <x-extend.bale-input id="current_password" type="password" label="current password" class="block w-full mt-1"
                wire:model="state.current_password" autocomplete="off" />
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div class="mb-4 sm:mb-6">
            <x-extend.bale-input id="password" type="password" label="password" class="block w-full mt-1"
                wire:model="state.password" autocomplete="off" />
            <x-input-error for="password" class="mt-2" />
        </div>

        <div class="mb-4 sm:mb-6">
            <x-extend.bale-input id="password_confirmation" type="password" label="password confirmation"
                class="block w-full mt-1" wire:model="state.password_confirmation" autocomplete="off" />
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>

        <x-extend.loading-container target="updateProfileInformation" class="justify-end mt-3">
            <x-extend.bale-button type="submit" label="Update" spinner useDisabledButton />
        </x-extend.loading-container>

    </form>
</div>
