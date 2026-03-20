<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAgentHasCategoryTargets extends Model
{
    protected $table = 'ad_agent_has_category_targets';
    protected $fillable = [
        'agent_id',
        'pm_product_category_id',
        'target_amount',
        'target_percentage',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function category()
    {
        return $this->belongsTo(PmProductCategory::class, 'pm_product_category_id');
    }
}
