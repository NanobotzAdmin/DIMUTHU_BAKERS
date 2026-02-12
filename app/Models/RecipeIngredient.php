<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredient extends Model
{
    use HasFactory;

    protected $table = 'pm_recipe_ingredients';

    protected $fillable = [
        'recipe_id',
        'product_item_id',
        'quantity',
        'unit',
        'type',
        'sort_order',
        'name',
        'is_aged',
        'aged_days',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'product_item_id');
    }

    // Accessor to get the ingredient name either from product item or the name field
    public function getIngredientNameAttribute()
    {
        if ($this->productItem) {
            return $this->productItem->product_name;
        }
        return $this->name;
    }
}
