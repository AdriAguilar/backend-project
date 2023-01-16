<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = 12;
        for ($i=0; $i < $rows; $i++) {             
            DB::table('products')->insert([
                'name' => fake()->name(),
                'description' => fake()->text(),
                'price' => fake()->randomFloat(2),
                'quantity' => fake()->numberBetween(0, 10),
                'stock' => fake()->boolean(),
                'images' => fake()->imageUrl(),
                'user_id' => User::all()->random()->id
            ]);
        }
    }
}
