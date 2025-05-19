<div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        @if ($this->enabled)
            @if ($showingConfirmation)
                {{ __('Finish enabling two factor authentication.') }}
            @else
                {{ __('You have enabled two factor authentication.') }}
            @endif
        @else
            {{ __('You have not enabled two factor authentication.') }}
        @endif
    </h3>

    <div class="mt-3 text-sm text-gray-600 text-pretty dark:text-gray-400">
        <p>
            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
        </p>
    </div>

    @if ($this->enabled)
        @if ($showingQrCode)
            <div class="mt-4 text-sm text-gray-600 text-pretty dark:text-gray-400">
                <p class="font-semibold">
                    @if ($showingConfirmation)
                        {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                    @else
                        {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                    @endif
                </p>
            </div>

            <div class="inline-block p-2 mt-4 bg-white">
                {!! $this->user->twoFactorQrCodeSvg() !!}
            </div>

            <div class="max-w-xl mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p class="font-semibold">
                    {{ __('Setup Key') }}: {{ decrypt($this->user->two_factor_secret) }}
                </p>
            </div>

            @if ($showingConfirmation)
                <div class="mt-4">
                    <x-label for="code" value="{{ __('Code') }}" />

                    <x-bale.input id="code" type="text" name="code" class="block w-1/2 mt-1"
                        inputmode="numeric" autofocus autocomplete="one-time-code" wire:model="code"
                        wire:keydown.enter="confirmTwoFactorAuthentication" />

                    <x-input-error for="code" class="mt-2" />
                </div>
            @endif
        @endif

        @if ($showingRecoveryCodes)
            <div class="max-w-xl mt-4 text-sm text-gray-600 dark:text-gray-400">
                <p class="font-semibold">
                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>
            </div>

            <div x-cloak
                class="grid max-w-xl gap-1 px-4 py-4 mt-4 font-mono text-sm bg-gray-100 rounded-lg dark:bg-gray-900 dark:text-gray-100">
                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                    <div>{{ $code }}</div>
                @endforeach
            </div>
        @endif
    @endif

    <div class="flex items-center justify-end mt-5">
        @if (!$this->enabled)
            <x-confirms-password wire:then="enableTwoFactorAuthentication">
                <x-bale.button type="button" label="enable" wire:loading.attr="disabled" />
            </x-confirms-password>
        @else
            @if ($showingRecoveryCodes)
                <x-confirms-password wire:then="regenerateRecoveryCodes">
                    <x-bale.secondary-button label="Regenerate Recovery Codes" class="me-3" />
                </x-confirms-password>
            @elseif ($showingConfirmation)
                <x-confirms-password wire:then="confirmTwoFactorAuthentication">
                    <x-bale.button type="button" label="Confirm" class="me-3" wire:loading.attr="disabled" />
                </x-confirms-password>
            @else
                <x-confirms-password wire:then="showRecoveryCodes">
                    <x-bale.secondary-button label="Show Recovery Codes" class="me-3" spinner />
                </x-confirms-password>
            @endif

            @if ($showingConfirmation)
                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-bale.secondary-button label="cancel" wire:loading.attr="disabled" />
                </x-confirms-password>
            @else
                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-bale.danger-button label="disable" wire:loading.attr="disabled" />
                </x-confirms-password>
            @endif

        @endif
    </div>
</div>
