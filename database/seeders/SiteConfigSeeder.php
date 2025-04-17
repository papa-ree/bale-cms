<?php

namespace Database\Seeders;

use App\Models\SiteConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configs = array(
            array("id" => "theme_color", "value" => "blue"),
            array("id" => "site_name", "value" => "BALé CMS"),
            array("id" => "site_contact", "value" => json_encode(['address' => 'Jl. Ir. H Juanda No.198, Tonatan, Kec. Ponorogo, Kabupaten Ponorogo, Jawa Timur 63418', 'email' => 'kominfo.ponorogo.go.id', 'phone' => '0352-3592999'])),
            array("id" => "embed_map", "value" => "<iframe src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.229820871798!2d111.48619320820366!3d-7.8710036956506295!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e79a0300ef1521d%3A0x80bc599492cab4d5!2sDinas%20Komunikasi%20Informatika%20dan%20Statistik%20Kabupaten%20Ponorogo!5e0!3m2!1sen!2sid!4v1730518647104!5m2!1sen!2sid' width='600' height='450' style='border:0;' allowfullscreen='' loading='lazy' referrerpolicy='no-referrer-when-downgrade'></iframe>"),
            array("id" => "hero_use_slider", "value" => true),
            array("id" => "announcement_modal_size", "value" => 'lg'),
            array("id" => "site_logo", "value" => null),
            array("id" => "facebook_data", "value" => '==='),
            array("id" => "twitter_data", "value" => '==='),
            array("id" => "instagram_data", "value" => '==='),
            array("id" => "youtube_data", "value" => '==='),
            array("id" => "tiktok_data", "value" => '==='),
        );

        foreach ($configs as $config) {
           SiteConfig::create([
               'id' => $config['id'],
               'value' => $config['value'],
           ]);
        }

    }
}
