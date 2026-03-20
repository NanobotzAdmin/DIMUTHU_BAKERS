<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAgentHasItemTargets extends Model
{
    protected $table = 'ad_agent_has_item_targets';
    protected $fillable = [
        'agent_id',
        'pm_product_item_id',
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

    public function item()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
