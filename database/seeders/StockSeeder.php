<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $productIds = DB::table('products')->pluck('id');
        $warehouseIds = DB::table('warehouses')->pluck('id');

        foreach ($productIds as $productId) {
            foreach ($warehouseIds as $warehouseId) {
                DB::table('stocks')->insert([
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'stock' => rand(0, 100),
                ]);
            }
        }
    }
}
