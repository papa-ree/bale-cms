<?php

namespace Database\Seeders;

use App\Models\LandingPageManagement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandingPageManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $landing_pages = array(
            // hero section
            array("id" => "hero-title", "landing_page_value" => "Selamat datang di Landing Page", "landing_page_url" => null),
            array("id" => "hero-subtitle", "landing_page_value" => "Kami menyediakan CMS yang bisa digunakan untuk website kamu. Interface sederhana dan mudah dipakai", "landing_page_url" => null),
            array("id" => "hero-cta", "landing_page_value" => "Cek lebih lanjut", "landing_page_url" => "localhost"),
            array("id" => "hero-image", "landing_page_value" => "Hero Image", "landing_page_url" => null, "landing_page_media_url_mode" => true, "landing_page_media_url" => "https://images.unsplash.com/photo-1665686376173-ada7a0031a85?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=900&h=700&q=80"),
        );

        foreach ($landing_pages as $landing) {
           LandingPageManagement::create([
               'id' => $landing['id'],
               'landing_page_value' => $landing['landing_page_value'],
               'landing_page_url' => $landing['landing_page_url'],
               'landing_page_media_url_mode' => $landing['landing_page_media_url_mode'] ?? null,
               'landing_page_media_url' => $landing['landing_page_media_url'] ?? null,
           ]);
        }
    }
}
