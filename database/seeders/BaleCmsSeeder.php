<?php

namespace Paparee\BaleCms\Database\Seeders;

use Illuminate\Database\Seeder;

class BaleCmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            SiteConfigSeeder::class,
            PageSeeder::class,
            NavigationSeeder::class,
            ThemeManagementSeeder::class,
            ServiceSeeder::class,
            LandingPageManagementSeeder::class,
            PostSeeder::class,
        ]);
    }
}
