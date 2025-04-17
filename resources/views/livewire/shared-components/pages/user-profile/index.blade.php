<div>
    <x-extend.page-container>
        <div id="scrollspy-scrollable-parent-2"
            class="max-h-[77vh] overflow-y-auto 
            [&::-webkit-scrollbar]:w-2
            [&::-webkit-scrollbar-track]:rounded-full
            [&::-webkit-scrollbar-track]:bg-transparent
            [&::-webkit-scrollbar-thumb]:rounded-full
            [&::-webkit-scrollbar-thumb]:bg-gray-300
            dark:[&::-webkit-scrollbar-track]:bg-transparent
            dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500">
            <div class="grid grid-cols-6">

                <div class="col-span-1">
                    <h2 class="text-xl font-medium dark:text-white">{{ __('User Profile') }}</h2>

                    <ul class="sticky top-0" data-hs-scrollspy="#scrollspy-2"
                        data-hs-scrollspy-scrollable-parent="#scrollspy-scrollable-parent-2">
                        <li>
                            <a href="#photo-profile"
                                class="block py-0.5 text-sm font-medium leading-6 text-gray-700 hover:text-gray-900 focus:outline-none focus:text-blue-600 dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-blue-500 hs-scrollspy-active:text-blue-600 dark:hs-scrollspy-active:text-blue-500 active">
                                {{ __('Photo') }}
                            </a>
                        </li>
                        <li>
                            <a href="#profile-information"
                                class="block py-0.5 text-sm font-medium leading-6 text-gray-700 hover:text-gray-900 focus:outline-none focus:text-blue-600 dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-blue-500 hs-scrollspy-active:text-blue-600 dark:hs-scrollspy-active:text-blue-500">
                                {{ __('Profile Information') }}
                            </a>
                        </li>
                        <li>
                            <a href="#password"
                                class="block py-0.5 text-sm font-medium leading-6 text-gray-700 hover:text-gray-900 focus:outline-none focus:text-blue-600 dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-blue-500 hs-scrollspy-active:text-blue-600 dark:hs-scrollspy-active:text-blue-500">
                                {{ __('Update Password') }}
                            </a>
                        </li>
                        <li>
                            <a href="#twoFA"
                                class="block py-0.5 text-sm font-medium leading-6 text-gray-700 hover:text-gray-900 focus:outline-none focus:text-blue-600 dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-blue-500 hs-scrollspy-active:text-blue-600 dark:hs-scrollspy-active:text-blue-500">
                                {{ __('Two Factor Authentication') }}
                            </a>
                        </li>
                        <li>
                            <a href="#browserSession"
                                class="block py-0.5 text-sm font-medium leading-6 text-gray-700 hover:text-gray-900 focus:outline-none focus:text-blue-600 dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-blue-500 hs-scrollspy-active:text-blue-600 dark:hs-scrollspy-active:text-blue-500">
                                {{ __('Browser Session') }}
                            </a>
                        </li>
                    </ul>

                </div>

                <div class="col-span-5">
                    <div id="scrollspy-2" class="pr-3 space-y-6">
                        <div id="photo-profile" class="p-4 border border-gray-300 rounded-lg">
                            <livewire:pages.user-profile.section.update-photo-profile />
                        </div>

                        <div id="profile-information" class="p-4 border border-gray-300 rounded-lg">
                            <h3 class="mb-3 text-lg font-semibold dark:text-white">{{ __('Profile Information') }}</h3>
                            <livewire:pages.user-profile.section.update-profile-information />
                        </div>

                        <div id="password" class="p-4 border border-gray-300 rounded-lg">
                            <h3 class="mb-3 text-lg font-semibold dark:text-white">{{ __('Update Password') }}</h3>
                            <livewire:pages.user-profile.section.update-password />
                        </div>

                        <div id="twoFA" class="p-4 border border-gray-300 rounded-lg">
                            <h3 class="mb-3 text-lg font-semibold dark:text-white">
                                {{ __('Two Factor Authentication') }}
                            </h3>
                            <livewire:pages.user-profile.section.two-factor-authentication />
                        </div>

                        <div id="browserSession" class="p-4 border border-gray-300 rounded-lg">
                            <h3 class="mb-3 text-lg font-semibold dark:text-white">
                                {{ __('Browser Session') }}
                            </h3>
                            <livewire:pages.user-profile.section.browser-session />
                        </div>
                        {{-- 
                        --}}

                    </div>
                </div>

            </div>
        </div>
    </x-extend.page-container>
</div>
