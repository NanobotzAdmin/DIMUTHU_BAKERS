<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeInstruction extends Model
{
    use HasFactory;

    protected $table = 'pm_recipe_instructions';

    protected $fillable = [
        'recipe_id',
        'step_description',
        'step_number',
        'sort_order'
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
