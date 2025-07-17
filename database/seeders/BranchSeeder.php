<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar lokasi cabang beserta kota-nya
        $branches = [
            ['location' => 'Ciracas', 'city' => 'Jakarta'],
            ['location' => 'Condet', 'city' => 'Jakarta'],
            ['location' => 'Daan Mogot', 'city' => 'Jakarta'],
            ['location' => 'Gunung Sahari', 'city' => 'Jakarta'],
            ['location' => 'Hayam Wuruk', 'city' => 'Jakarta'],
            ['location' => 'Jatinegara', 'city' => 'Jakarta'],
            ['location' => 'Kamal', 'city' => 'Jakarta'],
             ['location'=> 'Kelapa Gading', 'city' => 'Jakarta'],
            ['location' => 'Klender', 'city' => 'Jakarta'],
            ['location' => 'Sunter', 'city' => 'Jakarta'],
            ['location' => 'Tambora', 'city' => 'Jakarta'],
            ['location' => 'Tanjung Duren', 'city' => 'Jakarta'],
        ];

        // Simpan atau perbarui data ke database
        foreach ($branches as $branch) {
            Branch::updateOrCreate(
                ['location' => $branch['location']], // kondisi unik
                ['city' => $branch['city']]          // nilai yang akan diperbarui atau disimpan
            );
        }
    }
}
