<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'pm_recipes';

    protected $fillable = [
        'name',
        'description',
        'category',
        'categories',
        'yield',
        'prep_time',
        'cost',
        'status',
        'version',
        'is_waste',
        'shelf_life',
        'shelf_life_unit',
        'image_paths',
        'product_item_id'
    ];

    protected $casts = [
        'image_paths' => 'array',
        'cost' => 'decimal:2'
    ];

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    public function instructions()
    {
        return $this->hasMany(RecipeInstruction::class)->orderBy('sort_order');
    }

    public function byproducts()
    {
        return $this->hasMany(RecipeByproduct::class);
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'product_item_id');
    }
}
