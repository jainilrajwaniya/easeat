<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = ["Rajasthan", "UP"];

        for ($i = 0; $i < count($arr); $i++) {
            DB::table('states')->insert([
                "country_id" => 5,
                "name" => $arr[$i],
            ]);
        }
    }
}
