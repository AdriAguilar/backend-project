<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Database\Seeders\RolesSeeder;
use Database\Seeders\UsersSeeder;
use Database\Seeders\CommentsSeeder;
use Database\Seeders\ProductsSeeder;
use Database\Seeders\CategoriesSeeder;
use Database\Seeders\PurchasesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(CommentsSeeder::class);
        $this->call(PurchasesSeeder::class);
    }
}
