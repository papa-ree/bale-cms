<?php

namespace Paparee\BaleCms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Paparee\BaleCms\App\Models\Tenant;
use Spatie\Permission\Models\Role;

class CreateTenantCommand extends Command
{
    protected $signature = 'tenant:create';
    protected $description = 'Create a new tenant user and initialize their database';

    public function handle()
    {
        $name = $this->ask('Full Name');
        $username = $this->ask('Username');
        $email = $this->ask('Email');
        $password = $this->secret('Password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|alpha:ascii|unique:users,username',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1; // Exit with error code
        }

        // Create global user
        $user = User::create([
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $dbName = 'bale_tenant_' . $username;

        // Assign role
        if (!Role::where('name', 'tenant')->exists()) {
            Role::create(['name' => 'tenant']);
        }
        $user->assignRole('tenant');

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
            'user_uuid' => $user->uuid,
            'database' => $dbName,
            'status' => 'active',
        ]);

        $this->info("Tenant `$name` created with database `$dbName` and assigned role `tenant`.");
    }
}
