<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = User::create([
            'uuid' => Uuid::uuid7(),
            'name' => 'dev',
            'username' => 'dev',
            'email' => 'dev@balecms.com',
            'email_verified_at' => date("Y-m-d H:i:s"),
            'password' => '$2y$10$WKJU.3K5/8nMxwrTZKnKa.tO9IfGeX.pNJrkMBdNzoq9DCCJ2fI6e',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        $developer->assignRole('developer');

        $admin = User::create([
            'uuid' => Uuid::uuid7(),
            'name' => 'bale admin',
            'username' => 'baleadmin',
            'email' => 'admin@balecms.com',
            'email_verified_at' => date("Y-m-d H:i:s"),
            'password' => '$2y$12$Bd05IQ9pSwmun7sYzaconeGl6phm6VtnKt3nYnW127JS7ct84gMOa',
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        $admin->assignRole('admin');
    }
}
