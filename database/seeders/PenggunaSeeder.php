<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pengguna::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), // jangan lupa di-hash
            'role' => 'admin',
        ]);

        Pengguna::create([
            'name' => 'User Satu',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
    }
}
