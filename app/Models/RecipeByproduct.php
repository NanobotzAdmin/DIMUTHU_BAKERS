<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeByproduct extends Model{
    
    protected $table = 'pm_recipe_byproducts';

    protected $fillable = [
        'recipe_id',
        'product_item_id',
        'product_name',
        'quantity',
        'unit',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function nrv()
    {
        return $this->hasOne(RecipeByproductNrv::class);
    }
}
