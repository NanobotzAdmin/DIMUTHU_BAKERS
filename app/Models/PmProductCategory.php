<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmProductCategory extends Model
{
    protected $table = 'pm_product_category';
    protected $fillable = [
        'category_name',
        'category_code',
        'category_description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function agentCategoryTargets()
    {
        return $this->hasMany(AddAgentHasCategoryTargets::class, 'pm_product_category_id');
    }
}
