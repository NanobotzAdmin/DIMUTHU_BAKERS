<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeByproductNrv extends Model
{
    protected $table = 'pm_recipe_byproduct_nrvs';

    protected $fillable = [
        'recipe_byproduct_id',
        'product_item_id',
        'product_name',
        'market_value',
        'processing_cost',
    ];

    public function byproduct()
    {
        return $this->belongsTo(RecipeByproduct::class, 'recipe_byproduct_id');
    }
}
