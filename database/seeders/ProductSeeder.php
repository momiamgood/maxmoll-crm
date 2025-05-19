<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 100) as $i) {
            DB::table('products')->insert([
                'name' => "Товар {$i}",
                'price' => rand(100, 10000) / 100,
            ]);
        }
    }
}
