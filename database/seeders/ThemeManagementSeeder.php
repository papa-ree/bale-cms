<?php

namespace Database\Seeders;

use App\Models\ThemeManagement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $themes = array(
            array("theme_name" => "default-livewire", 'theme_vendor' => 'default-livewire', "actived" => false),
            array("theme_name" => "uno", 'theme_vendor' => 'uno', "actived" => false),
            array("theme_name" => "organisasi", 'theme_vendor' => 'organisasi', "actived" => true),
           );

        foreach ($themes as $theme) {
           ThemeManagement::create([
               'theme_name' => $theme['theme_name'],
               'theme_vendor' => $theme['theme_vendor'],
               'actived' => $theme['actived'],
           ]);
        }
    }
}
