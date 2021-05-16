<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 1000;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('users')->insert([//,
                'phone_number' => '4567897623',
                'device_token' => '45sdg6789762sd3',
                'first_name' => substr($faker->name, 0, 25),
                'last_name' => substr($faker->name, 0, 25),
                'name' => substr($faker->name, 0, 50),
                'email' => $faker->unique()->email,
                'password' => Hash::make(str_random(10)),
                'status' => 'Active'
            ]);
        }
    }
}
