<div>
    <form wire:submit='updateProfileInformation' x-cloak>

        <div class="mb-4 sm:mb-6">
            <x-extend.bale-input wire:model="name" label="name" required />
            <x-input-error for="name" class="mt-2" />
        </div>

        <div class="mb-4 sm:mb-6">
            <x-extend.bale-input wire:model="username" label="username" required />
            <x-input-error for="username" class="mt-2" />
        </div>

        <div class="mb-4 sm:mb-6">
            <x-extend.bale-input wire:model="email" type="email" label="email" required />
            <x-input-error for="email" class="mt-2" />
        </div>

        <x-extend.loading-container target="updateProfileInformation" class="justify-end mt-3">
            <x-extend.bale-button type="submit" label="Update" spinner useDisabledButton />
        </x-extend.loading-container>

    </form>

</div>
