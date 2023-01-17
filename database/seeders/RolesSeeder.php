<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Admin',
            'description' => 'Can moderate the website'
        ]);

        DB::table('roles')->insert([
            'name' => 'Client',
            'description' => 'Just can buy products'
        ]);

        DB::table('roles')->insert([
            'name' => 'Seller',
            'description' => 'Just can sell products'
        ]);
    }
}
