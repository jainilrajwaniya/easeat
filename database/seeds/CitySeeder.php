<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->insert([
            "state_id" => 1,
            "name" => 'Udaipur',
        ]);
        
        DB::table('cities')->insert([
            "state_id" => 1,
            "name" => 'Falna',
        ]);
        
        DB::table('cities')->insert([
            "state_id" => 2,
            "name" => 'Allahabad',
        ]);
    }
}
