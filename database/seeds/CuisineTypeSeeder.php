<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuisineTypeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
        "Afghani",
        "African","American","Andhra","Arabic","Armenian","Asian","Assamese","Australian","Awadhi","Bakery","Bangladeshi","Barbecue","Belgian","Bengali","Beverages","Bihari","Biryani","Brasserie","British","Bubble Tea","Burger","Burmese","Cafe","Charcoal Chicken","Charcoal Grill","Chettinad","Chili","Chinese","Continental","Cuisine Varies","Desserts","Drinks Only","Ethiopian","European","Fast Food","Fijian","Finger Food","French","German","Goan","Greek","Gujarati","Healthy Food","Hot Pot","Hyderabadi","Ice Cream","Illocano","Indian","Indonesian","Iranian","Irish","Italian","Japanese","Juices","Kashmiri","Kebab","Kerala","Konkan","Korean","Lebanese","Lucknowi","Maharashtrian","Malaysian","Malwani","Mangalorean","Mediterranean","Mexican","Middle Eastern","Mithai","Modern Australian","Modern Indian","Mongolian","Moroccan","Mughlai","Naga","Native Australian","Nepalese","North Eastern","North Indian","Oriya","Pakistani","Panini","Parsi","Persian","Pizza","Portuguese","Rajasthani","Raw Meats","Roast Chicken","Rolls","Russian","Salad","Sandwich","Seafood","Sindhi","Singaporean","South American","South Indian","Spanish","Sri Lankan","Steak","Street Food","Sushi","Tea","Tex-Mex","Thai","Tibetan","Turkish","Vietnamese","Oriental","Snacks","Coffee","Speciality Coffee","Kuwaiti","Egyptian","Frozen Yogurt","Shawarma &amp; Doner","Brazilian","kunafah","Organic","Steak House","Vegan","International","Fried Chicken"];

        for ($i = 0; $i < count($arr) - 1; $i++) {
            DB::table('cuisine_types')->insert([
                "cuisine_type_name" => $arr[$i]
            ]);
        }
    }
}
