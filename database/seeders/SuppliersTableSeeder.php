<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('suppliers')->insert([
            ['id' => 1, 'name' => 'Supplier 1', 'cnpj' => '00.000.000/0000-00', 'contact' => '(44) 99999-9999']
        ]);
    }
}
