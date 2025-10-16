<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        Produk::create([
            'title' => 'Laptop Gaming',
            'description' => 'Laptop spek tinggi untuk gaming',
            'price' => 15000000,
            'photo' => 'laptop.jpg',
            'status' => 'available',
            'user_id' => 1
        ]);

        Produk::create([
            'title' => 'Mouse Wireless',
            'description' => 'Mouse tanpa kabel',
            'price' => 250000,
            'photo' => 'mouse.jpg',
            'status' => 'available',
            'user_id' => 2
        ]);
    }
}
