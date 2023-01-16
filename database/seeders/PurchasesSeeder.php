<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = 6;
        for ($i=0; $i < $rows; $i++) {             
            DB::table('users')->insert([
                'product_id' => Product::all()->random()->id,
                'user_id' => User::all()->random()->id,
                'address' => fake()->address(),
                'status' => fake()->randomElement(['pending', 'completed' , 'canceled']),
                'payment_method' => fake()->randomElement(['Paypal', 'Credit Card']),
                'invoice_number' => Str::random(12)
            ]);
        }
    }
}
