<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kitchenAddOnCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'kitchen_add_on_category';

    protected $fillable = [
        'kitchen_item_id','category_name_en','category_name_ar','min','max','cat_seq_no', 'choices', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
