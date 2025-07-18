<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Branch;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'type' => 1,
                'profile_photo' => null,
                'email_verified_at' => now(),
                'remember_token' => null,
            ],
            [
                'name' => 'User 1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'type' => 0,
                'profile_photo' => null,
                'email_verified_at' => now(),
                'remember_token' => null,
            ],
            [
                'name' => 'user2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
                'type' => 0,
                'profile_photo' => null,
                'email_verified_at' => now(),
                'remember_token' => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        // Seed Branches
        $branches = [
            // Area 1: Jakarta (H1)
            ['city' => 'Jakarta', 'location' => 'WAHANA Kelapa Gading, Jl. Boulevard Raya No. 12'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Sunter, Jl. Danau Sunter No. 45'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Ciracas, Jl. Raya Ciracas No. 78'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Jatinegara, Jl. Jatinegara Timur No. 23'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Kamal, Jl. Kamal Raya No. 56'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Daan Mogot, Jl. Daan Mogot No. 89'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Condet, Jl. Raya Condet No. 34'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Bintaro, Jl. Bintaro Utama No. 67'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Klender, Jl. I Gusti Ngurah Rai No. 90'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Gunung Sahari, Jl. Gunung Sahari No. 12'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Hayam Wuruk, Jl. Hayam Wuruk No. 45'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Tambora, Jl. Tambora Raya No. 78'],
            ['city' => 'Jakarta', 'location' => 'WAHANA Tanjung Duren, Jl. Tanjung Duren No. 23'],

            // Area 2: Tangerang (H23)
            ['city' => 'Tangerang', 'location' => 'WAHANA Ciputat, Jl. RE Martadinata No. 56'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Balaraja, Jl. Raya Balaraja No. 89'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Jatake, Jl. Industri Jatake No. 12'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Tanah Tinggi, Jl. Tanah Tinggi No. 45'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Cikokol, Jl. MH Thamrin No. 78'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Cipondoh, Jl. KH Hasyim Ashari No. 23'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Karang Mulya, Jl. Karang Mulya No. 56'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Cimone, Jl. Raya Cimone No. 89'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Jayanti, Jl. Raya Jayanti No. 12'],
            ['city' => 'Tangerang', 'location' => 'WAHANA Cisoka, Jl. Cisoka Raya No. 45'],
            ['city' => 'Tangerang', 'location' => 'AHASS WAHANA Kronjo, Jl. Raya Kronjo No. 89'],
            ['city' => 'Tangerang', 'location' => 'AHASS WAHANA Pinang, Jl. Raya Pinang No. 12'],
            ['city' => 'Tangerang', 'location' => 'AHASS WAHANA Pondok Aren, Jl. Pondok Aren No. 45'],
            ['city' => 'Tangerang', 'location' => 'AHASS WAHANA Curug, Jl. Raya Curug No. 78'],
            ['city' => 'Tangerang', 'location' => 'AHASS WAHANA Kukun, Jl. Kukun Raya No. 23'],

            // Area 3: Luar Kota (H123)
            ['city' => 'Luar Kota', 'location' => 'WAHANA Yogyakarta, Jl. Malioboro No. 78'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Bandung, Jl. Asia Afrika No. 23'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Medan Katamso, Jl. Katamso No. 56'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Medan Sunggal, Jl. Sunggal No. 89'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Palembang, Jl. Sudirman No. 12'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Kupang, Jl. Timor Raya No. 45'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Kotamobagu, Jl. Raya Kotamobagu No. 78'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Gorontalo, Jl. Sultan Botutihe No. 23'],
            ['city' => 'Luar Kota', 'location' => 'WAHANA Sumedang, Jl. Raya Sumedang No. 56'],
            ['city' => 'Luar Kota', 'location' => 'AHASS WAHANA Jangkang, Jl. Jangkang Raya No. 56'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}