<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Branch;

class DatabaseSeeder extends Seeder
{
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
            ['city' => 'Jakarta', 'name' => 'Kelapa Gading', 'address' => 'Jl. Boulevard Raya No. 12'],
            ['city' => 'Jakarta', 'name' => 'Sunter', 'address' => 'Jl. Danau Sunter No. 45'],
            ['city' => 'Jakarta', 'name' => 'Ciracas', 'address' => 'Jl. Raya Ciracas No. 78'],
            ['city' => 'Jakarta', 'name' => 'Jatinegara', 'address' => 'Jl. Jatinegara Timur No. 23'],
            ['city' => 'Jakarta', 'name' => 'Kamal', 'address' => 'Jl. Kamal Raya No. 56'],
            ['city' => 'Jakarta', 'name' => 'Daan Mogot', 'address' => 'Jl. Daan Mogot No. 89'],
            ['city' => 'Jakarta', 'name' => 'Condet', 'address' => 'Jl. Raya Condet No. 34'],
            ['city' => 'Jakarta', 'name' => 'Bintaro', 'address' => 'Jl. Bintaro Utama No. 67'],
            ['city' => 'Jakarta', 'name' => 'Klender', 'address' => 'Jl. I Gusti Ngurah Rai No. 90'],
            ['city' => 'Jakarta', 'name' => 'Gunung Sahari', 'address' => 'Jl. Gunung Sahari No. 12'],
            ['city' => 'Jakarta', 'name' => 'Hayam Wuruk', 'address' => 'Jl. Hayam Wuruk No. 45'],
            ['city' => 'Jakarta', 'name' => 'Tambora', 'address' => 'Jl. Tambora Raya No. 78'],
            ['city' => 'Jakarta', 'name' => 'Tanjung Duren', 'address' => 'Jl. Tanjung Duren No. 23'],

            // Area 2: Tangerang (H23)
            ['city' => 'Tangerang', 'name' => 'Ciputat', 'address' => 'Jl. RE Martadinata No. 56'],
            ['city' => 'Tangerang', 'name' => 'Balaraja', 'address' => 'Jl. Raya Balaraja No. 89'],
            ['city' => 'Tangerang', 'name' => 'Jatake', 'address' => 'Jl. Industri Jatake No. 12'],
            ['city' => 'Tangerang', 'name' => 'Tanah Tinggi', 'address' => 'Jl. Tanah Tinggi No. 45'],
            ['city' => 'Tangerang', 'name' => 'Cikokol', 'address' => 'Jl. MH Thamrin No. 78'],
            ['city' => 'Tangerang', 'name' => 'Cipondoh', 'address' => 'Jl. KH Hasyim Ashari No. 23'],
            ['city' => 'Tangerang', 'name' => 'Karang Mulya', 'address' => 'Jl. Karang Mulya No. 56'],
            ['city' => 'Tangerang', 'name' => 'Cimone', 'address' => 'Jl. Raya Cimone No. 89'],
            ['city' => 'Tangerang', 'name' => 'Jayanti', 'address' => 'Jl. Raya Jayanti No. 12'],
            ['city' => 'Tangerang', 'name' => 'Cisoka', 'address' => 'Jl. Cisoka Raya No. 45'],

            // Area 3: Luar Kota (H123)
            ['city' => 'Luar Kota', 'name' => 'Yogyakarta', 'address' => 'Jl. Malioboro No. 78'],
            ['city' => 'Luar Kota', 'name' => 'Bandung', 'address' => 'Jl. Asia Afrika No. 23'],
            ['city' => 'Luar Kota', 'name' => 'Medan Katamso', 'address' => 'Jl. Katamso No. 56'],
            ['city' => 'Luar Kota', 'name' => 'Medan Sunggal', 'address' => 'Jl. Sunggal No. 89'],
            ['city' => 'Luar Kota', 'name' => 'Palembang', 'address' => 'Jl. Sudirman No. 12'],
            ['city' => 'Luar Kota', 'name' => 'Kupang', 'address' => 'Jl. Timor Raya No. 45'],
            ['city' => 'Luar Kota', 'name' => 'Kotamobagu', 'address' => 'Jl. Raya Kotamobagu No. 78'],
            ['city' => 'Luar Kota', 'name' => 'Gorontalo', 'address' => 'Jl. Sultan Botutihe No. 23'],
            ['city' => 'Luar Kota', 'name' => 'Sumedang', 'address' => 'Jl. Raya Sumedang No. 56'],

            // City bebas, Category H23
            ['city' => 'Tangerang', 'name' => 'Kronjo', 'address' => 'Jl. Raya Kronjo No. 89'],
            ['city' => 'Tangerang', 'name' => 'Pinang', 'address' => 'Jl. Raya Pinang No. 12'],
            ['city' => 'Tangerang', 'name' => 'Pondok Aren', 'address' => 'Jl. Pondok Aren No. 45'],
            ['city' => 'Tangerang', 'name' => 'Curug', 'address' => 'Jl. Raya Curug No. 78'],
            ['city' => 'Tangerang', 'name' => 'Kukun', 'address' => 'Jl. Kukun Raya No. 23'],
            ['city' => 'Luar Kota', 'name' => 'Jangkang', 'address' => 'Jl. Jangkang Raya No. 56'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}