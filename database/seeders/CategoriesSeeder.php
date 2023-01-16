<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            'name' => 'PC'
        ]);
        
        DB::table('categories')->insert([
            'name' => 'PS5'
        ]);

        DB::table('categories')->insert([
            'name' => 'Switch'
        ]);
    }
}
