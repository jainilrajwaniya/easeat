<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = ["England", "Northern Ireland", "Scotland", "Wales", "India"];

        for ($i = 0; $i < count($arr); $i++) {
            DB::table('countries')->insert([
                "name" => $arr[$i],
                "currency" => 'GBP',
            ]);
        }
    }
}
