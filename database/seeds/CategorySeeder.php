<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = ["Breakfast", "Lunch", "Dinner", "Cafes", "Snacks", "Gluten Free"];

        for ($i = 0; $i < count($arr) - 1; $i++) {
            DB::table('categories')->insert([
                "category_name" => $arr[$i]
            ]);
        }
    }
}
