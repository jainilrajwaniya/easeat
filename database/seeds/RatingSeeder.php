<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RatingSeeder extends Seeder
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
        $ratingArr = [1, 1.5, 2, 2,5, 3, 3.5, 4, 4.5, 5];
        for ($i = 0; $i < $limit; $i++) {
            DB::table('ratings')->insert([//,
                'user_id' => rand(1, 1000),
                'kitchen_id' => rand(1, 10),
                'rating' => $ratingArr[rand(0, 7)],
                'status' => 'Active'
            ]);
        }
    }
}
