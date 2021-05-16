<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeKitchenAssoc extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kitchen_id', 'chef_id', 'promo_code_id', 'created_at', 'updated_at' 
    ];
    
    /**
     * Check promocodes is not expired, assoc. with kitchen
     * @return type
     */
    public static function checkPromoCodeAssoc($id, $kitchenId) {
        return Self::Where(['promo_code_id' => $id, 'kitchen_id' => $kitchenId])->select(['id'])->get()->toArray();
    }
}
