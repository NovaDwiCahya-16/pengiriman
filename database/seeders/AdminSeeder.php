<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek kalau admin sudah ada
        $adminEmail = 'kepalagudang@gmail.com';

        if (!User::where('email', $adminEmail)->exists()) {
            User::create([
                'name' => 'Kepala Gudang',
                'email' => $adminEmail,
                'password' => Hash::make('12345678'), // Password admin
                'type' => 1, // Admin
            ]);
        }
    }
}
