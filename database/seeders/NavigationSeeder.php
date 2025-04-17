<?php

namespace Database\Seeders;

use App\Models\Navigation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $navs = array(
            array("id" => "9c01ab8a-72fd-469e-b55f-8dacdd312001", "navigation_name" => "PPID", "navigation_order" => "1", "parent_id" => null, "page_slug" => "ppid"),

            array("id" => "9c01ab8a-72fd-469e-b55f-8dacdd312002", "navigation_name" => "Laporan", "navigation_order" => "2", "parent_id" => null, "page_slug" => "laporan"),
            array("id" => "9c01ab8a-72fd-469e-b55f-001cdd312002", "navigation_name" => "Kinerja", "navigation_order" => "1", "parent_id" => "9c01ab8a-72fd-469e-b55f-8dacdd312002", "page_slug" => "kinerja"),
        );

        foreach ($navs as $nav) {
           Navigation::create([
               'id' => $nav['id'],
               'navigation_name' => $nav['navigation_name'],
               'navigation_slug' => Str::of($nav['navigation_name'])->slug('-'),
               'navigation_order' => $nav['navigation_order'],
               'parent_id' => $nav['parent_id'],
               'page_slug' => $nav['page_slug'],
           ]);
        }
    }
}
