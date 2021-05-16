<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    protected $table = 'areas';
    protected $fillable = ['name', 'city_id', 'created_by', 'updated_by'];
    /**
     * get all area with city, state, country
     * @return type
     */
    public static function getAllAreas() {
        $selectArray = ['states.name as state','countries.name as country', 
            'cities.name as city', 'cities.id as city_id', 'areas.name as area','areas.id as id'];
        
        $list = self::select($selectArray)
                ->leftJoin('cities', 'cities.id', '=', 'areas.city_id')
                ->leftJoin('states', 'states.id', '=', 'cities.state_id')
                ->leftJoin('countries', 'countries.id', '=', 'states.country_id')
                ->get();
        return $list;
    }
    
    /**
     * check whether area already used
     * @param type $id
     * @param type $name
     * @return type
     */
    public static function checkAreaExists($id, $name) {
       return self::where([['id' , '!=', $id], ['name', '=', $name]])->count(); 
    }
}
