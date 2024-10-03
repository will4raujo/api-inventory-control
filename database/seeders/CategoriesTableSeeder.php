<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => 1, 'name' => 'Electronics'],
            ['id' => 2, 'name' => 'Clothing'],
            ['id' => 3, 'name' => 'Books'],
            ['id' => 4, 'name' => 'Furniture'],
            ['id' => 5, 'name' => 'Appliances'],
            ['id' => 6, 'name' => 'Toys'],
            ['id' => 7, 'name' => 'Tools'],
            ['id' => 8, 'name' => 'Automotive'],
            ['id' => 9, 'name' => 'Health & Beauty'],
            ['id' => 10, 'name' => 'Sports & Outdoors']
        ]);
    }
}
