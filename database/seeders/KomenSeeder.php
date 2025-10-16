<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komen;

class KomenSeeder extends Seeder
{
    public function run(): void
    {
        Komen::create([
            'user_id' => 2,
            'product_id' => 1,
            'content' => 'Laptopnya mantap!',
            'sentiment' => 'positif'
        ]);

        Komen::create([
            'user_id' => 1,
            'product_id' => 2,
            'content' => 'Mouse enak dipakai',
            'sentiment' => 'positif'
        ]);
    }
}
