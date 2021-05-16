<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = "states";
    
    protected $fillable = ['name', 'country_id', 'created_by', 'updated_by'];
    
    /**
     * get all states with country
     * @return type
     */
    public static function getAllStates() {
        $selectArray = ['states.name as state','countries.name as country',
            'countries.id as country_id', 'states.id as id'];
        
        $list = self::select($selectArray)
                ->leftJoin('countries', 'countries.id', '=', 'states.country_id')
                ->get();
        return $list;
    }
    
    /**
     * check whether state already used
     * @param type $id
     * @param type $name
     * @return type
     */
    public static function checkStateExists($id, $name) {
       return self::where([['id' , '!=', $id], ['name', '=', $name]])->count(); 
    }
    
    
}
