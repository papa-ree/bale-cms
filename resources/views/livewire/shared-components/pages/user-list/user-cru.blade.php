<?php

use function Livewire\Volt\{state, title, mount, computed, rules};
use App\Models\User;
use Spatie\Permission\Models\Role;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Artisan;
use Paparee\BaleCms\App\Models\Tenant;

state(['name', 'username', 'email', 'password', 'role_name', 'user', 'edit_mode' => false, 'change_password' => true]);

title(fn() => $this->edit_mode ? 'Edit User' : 'Create User');

mount(function ($user) {
    // dump(
    //     url()
    //         ->current()
    //         ->hasAny(['create']),
    // );
    $this->authorize('user management');

    $this->user = User::whereUuid($user)->first();

    if ($this->user) {
        $this->edit_mode = true;
        $this->change_password = false;
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->role_name = $this->user->roles->first()->name;
    }
});

rules(
    fn() => [
        'name' => 'required|string|min:3|max:50',
        'username' => ['required', 'string', 'alpha_num:ascii', Rule::unique('users')->ignore($this->user)],
        'email' => ['required', 'string', 'email:rfc,filter', Rule::unique('users')->ignore($this->user)],
        'password' => ['required_if_accepted:change_password', Rule::when($this->change_password, [Password::default()])],
        'role_name' => 'required',
    ],
);

$availableRoles = computed(function () {
    return Role::whereNotIn('name', ['developer'])->get('name');
});

$store = function (LivewireAlert $alert) {
    $this->authorize('user management');

    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $user->assignRole($this->role_name);

        DB::commit();
        session()->flash('saved', [
            'title' => 'Changes Saved!',
        ]);

        $this->redirect('user-lists', navigate: true);
    } catch (\Throwable $th) {
        $this->dispatch('disabling-button', params: false);

        DB::rollBack();
        info($th->getMessage());
        $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
    }

    if ($this->role_name == 'tenant') {
        $this->createTenant($user->uuid);
    }
};

$createTenant = function ($uuid) {
    $dbName = 'bale_tenant_' . $this->username . '_' . uniqid();

    // Create database
    DB::statement("CREATE DATABASE `$dbName`");

    // Ambil konfigurasi tenant dari config/bale-cms.php
    $tenantConfig = config('bale-cms.database.tenant');

    // Set nama database
    $tenantConfig['database'] = $dbName;

    // Tambahkan konfigurasi tenant ke runtime Laravel
    Config::set('database.connections.tenant', $tenantConfig);

    DB::purge('tenant');
    DB::reconnect('tenant');

    // Run tenant migrations
    Artisan::call('migrate', [
        '--database' => 'tenant',
        '--path' => 'database/migrations/tenant',
        '--force' => true,
    ]);

    // Save tenant record
    Tenant::create([
        'user_uuid' => $uuid,
        'database' => $dbName,
        'status' => 'active',
    ]);

    return 1;
};

$update = function (LivewireAlert $alert) {
    $this->authorize('user management');

    $this->validate();

    DB::beginTransaction();

    try {
        $this->dispatch('disabling-button', params: true);

        $this->user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
        ]);

        if ($this->change_password) {
            $this->user->update([
                'password' => bcrypt($this->password),
            ]);
        }

        $this->user->assignRole($this->role_name);

        DB::commit();
        session()->flash('saved', [
            'title' => 'Changes Saved!',
        ]);

        $this->redirect('user-lists', navigate: true);
    } catch (\Throwable $th) {
        $this->dispatch('disabling-button', params: false);

        DB::rollBack();
        info($th->getMessage());
        $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
    }
};

?>

<div>
    <x-bale.back-breadcrumb :href="route('user-lists.index')" label="user list" />

    <x-bale.page-container class="lg:max-w-[800px] lg:mx-auto">
        <div class="mb-6 md:flex md:justify-between md:items-center">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                {{ $edit_mode ? 'Edit User' : 'Create New User' }}
            </h2>
        </div>

        <form wire:submit="{{ $edit_mode ? 'update' : 'store' }}" x-data="{
            balepassword: $wire.entangle('password').live,
            copied: false,
            disabledCopyButton: true,
            changePassword: $wire.entangle('change_password'),
        }">
            <div class="mb-4 sm:mb-6">
                <x-bale.input wire:model='name' placeholder="name" label="name" required autofocus />
                <x-input-error for="name" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.input wire:model='username' placeholder="username" label="username" required
                    autocomplete="off" />
                <x-input-error for="username" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.input wire:model='email' type="email" placeholder="email" label="email" type="email"
                    required />
                <x-input-error for="email" />
            </div>

            @if ($edit_mode)
                <div class="flex mb-4 sm:mb-6">
                    <input type="checkbox" wire:model.live='change_password'
                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-emerald-600 focus:ring-emerald-500 checked:border-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-gray-800"
                        id="bale-change-password-checkbox">
                    <label for="bale-change-password-checkbox" class="text-sm ms-3 dark:text-neutral-400">Change
                        Password</label>
                </div>
            @endif

            <div class="relative mb-4 sm:mb-6">
                <div class=""
                    :class="changePassword ? '' :
                        'absolute top-0 rounded-lg start-0 size-full z-10 bg-white/50 dark:bg-neutral-800/40'">
                </div>
                <x-bale.input wire:model='password' x-model="balepassword" placeholder="password" label="password"
                    usePasswordField useGenPassword />
                <x-input-error for="password" />
            </div>

            <div class="mb-4 sm:mb-6">
                <x-bale.select-dropdown label="select role" x-data="{ roleName: $wire.entangle('role_name') }">
                    <x-slot name="defaultValue">
                        <span x-text="roleName == null ? 'Open this select menu' : roleName"></span>
                    </x-slot>
                    @foreach ($this->availableRoles as $role)
                        <label for="{{ $role->name }}"
                            class="flex w-full p-3 text-sm transition duration-200 ease-out bg-white hover:bg-gray-200 hover:rounded-lg dark:bg-neutral-900 hover:dark:border-neutral-700 dark:text-neutral-400"
                            wire:key="{{ $role->name }}" @click="title='{{ $role->name }}'">
                            <span class="text-sm text-gray-500 dark:text-neutral-400">{{ $role->name }}</span>
                            <input type="radio" name="role_name" wire:model='role_name' value="{{ $role->name }}"
                                class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                id="{{ $role->name }}">
                        </label>
                    @endforeach
                </x-bale.select-dropdown>
                <x-input-error for="role_name" />
            </div>

            <div class="flex items-center justify-end"><x-bale.button type="submit"
                    label="{{ $edit_mode ? 'update' : 'store' }}" /></div>
        </form>
    </x-bale.page-container>
</div>
