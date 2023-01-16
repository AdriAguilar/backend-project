<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = 25;
        for ($i=0; $i < $rows; $i++) {             
            DB::table('comments')->insert([
                'content' => fake()->text(),
                'stars' => fake()->numberBetween(1, 5),
                'product_id' => Product::all()->random()->id,
                'user_id' => User::all()->random()->id
            ]);
        }
    }
}
