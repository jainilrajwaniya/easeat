<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = "cities";
    protected $fillable = ['name', 'name_ar', 'state_id', 'created_by', 'updated_by'];
    /**
     * get all city with state, country
     * @return type
     */
    public static function getAllCities() {
        $selectArray = ['states.name as state', 'states.id as state_id','countries.name as country', 
            'cities.name as city', 'cities.id as id'];
        
        $list = self::select($selectArray)
                ->leftJoin('states', 'states.id', '=', 'cities.state_id')
                ->leftJoin('countries', 'countries.id', '=', 'states.country_id')
                ->get();
        return $list;
    }
    
    /**
     * check whether city already used
     * @param type $id
     * @param type $name
     * @return type
     */
    public static function checkCityExists($id, $name) {
       return self::where([['id' , '!=', $id], ['name', '=', $name]])->count(); 
    }
}
