<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
class KitchenItemVarients extends Model
{
     protected $table="kitchen_item_varients";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'kitchen_item_id', 'varient_name', 'varient_name_ar', 'varient_price','created_at', 'updated_at' 
    ];
    
}