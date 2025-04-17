<div>
    <form wire:submit='updateProfileInformation' x-cloak>
        <div x-data="{ photoName: null, photoPreview: null, uploading: false, progress: 0, showUpdateButton: false, showUploadButton: true }" x-on:livewire-upload-start="uploading = true"
            x-on:livewire-upload-finish="uploading = false; showUpdateButton = true; showUploadButton = false"
            x-on:livewire-upload-cancel="uploading = false; showUpdateButton = false; showUploadButton = true"
            x-on:livewire-upload-error="uploading = false; showUpdateButton = false; showUploadButton = true"
            x-on:livewire-upload-progress="progress = $event.detail.progress" class="col-span-6 sm:col-span-4">

            <!-- Profile Photo File Input -->
            <input type="file" id="photo" class="hidden" wire:model.live="photo"
                accept="image/png, image/jpeg, image/jpg"" x-ref="photo"
                x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                        " />

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-x-4">
                    {{-- Current Profile Photo --}}
                    <div class="" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}"
                            class="object-cover w-20 h-20 rounded-full ring-2 ring-gray-200 dark:ring-neutral-900">
                    </div>

                    {{-- New Profile Photo Preview --}}
                    <div class="" x-show="photoPreview" style="display: none;">
                        <span
                            class="block w-20 h-20 bg-center bg-no-repeat bg-cover rounded-full ring-2 ring-gray-200 dark:ring-neutral-900"
                            x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                        </span>
                    </div>

                    <div class="">
                        <p class="font-semibold me-2">{{ $this->user->name }}</p>
                        <p class="mt-2 text-sm text-gray-400 me-2">{{ $this->user->username }}</p>
                    </div>
                </div>

                <div class="flex gap-x-2">
                    <div x-on:show-update-button.window="showUpdateButton = $event.detail.params"
                        x-on:show-upload-button.window="showUploadButton = $event.detail.params"></div>
                    <x-extend.bale-secondary-button x-show="showUploadButton" label="upload photo" type="button"
                        x-on:click.prevent="$refs.photo.click()" />
                    <x-extend.bale-button type="submit" x-show="showUpdateButton" label="update photo" />
                </div>

            </div>

            <div x-show="uploading">
                <progress max="100" x-bind:value="progress"></progress>
            </div>

            <x-input-error for="photo" class="mt-2" />
        </div>
    </form>

</div>
