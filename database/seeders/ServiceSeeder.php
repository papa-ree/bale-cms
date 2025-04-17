<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = array(
            array("service_name" => "kependudukan", "service_description" => "Layanan kependudukan di kecamatan membantu warga mengurus KTP, KK, dan akta kelahiran secara cepat tanpa ke dinas lebih tinggi.", "service_order" => "1", "service_icon" => "activity"),
            array("service_name" => "perijinan", "service_description" => "Layanan perijinan memudahkan warga mengurus izin keramaian dan tempat tinggal, tanpa perlu ke instansi lebih tinggi.", "service_order" => "2", "service_icon" => "bike"),
            array("service_name" => "pelaporan", "service_description" => "Layanan pelaporan di kecamatan memfasilitasi warga untuk melaporkan masalah seperti keamanan, bencana, dan administrasi, yang akan ditindaklanjuti oleh pihak terkait.", "service_order" => "3", "service_icon" => "album"),
        );

        foreach ($services as $page) {
           Service::create([
               'service_name' => $page['service_name'],
               'service_order' => $page['service_order'],
               'service_description' => $page['service_description'],
               'service_icon' => $page['service_icon'],
           ]);
        }
    }
}
