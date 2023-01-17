<?php

namespace Database\Seeders;

use App\Models\Purchase;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rows = 10;
        for ($i=0; $i < $rows; $i++) {             
            DB::table('users')->insert([
                'name' => fake()->name(),
                'username' => fake()->userName(),
                'email' => fake()->email(),
                'password' => fake()->password(),
                'image' => Str::random(),
                'role_id' => Role::all()->random()->id,
            ]);
        }
    }
}
