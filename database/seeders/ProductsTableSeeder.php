<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'id' => 1,
                'name' => 'Laptop',
                'code' => 123456,
                'description' => 'A laptop computer',
                'category_id' => 1,
                'supplier_id' => 1,
                'cost_price' => 500.00,
                'sale_price' => 800.00,
                'stock' => 10,
                'min_stock' => 5,
                'max_stock' => 20,
                'expiration_date' => now()->addYear()
            ],
            [
                'id' => 2,
                'name' => 'T-shirt',
                'code' => 654321,
                'description' => 'A cotton t-shirt',
                'category_id' => 2,
                'supplier_id' => 1,
                'cost_price' => 10.00,
                'sale_price' => 20.00,
                'stock' => 50,
                'min_stock' => 25,
                'max_stock' => 100,
                'expiration_date' => now()->addYear()
            ],
            [
                'id' => 3,
                'name' => 'Book',
                'code' => 987654,
                'description' => 'A book',
                'category_id' => 3,
                'supplier_id' => 1,
                'cost_price' => 5.00,
                'sale_price' => 10.00,
                'stock' => 100,
                'min_stock' => 50,
                'max_stock' => 200,
                'expiration_date' => now()->addYear()
            ]
        ]);
    }
}
