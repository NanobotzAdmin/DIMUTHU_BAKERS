<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAgentHasItemTargets extends Model
{
    protected $table = 'ad_agent_has_item_targets';
    protected $fillable = [
        'monthly_target_id',
        'pm_product_item_id',
        'target_amount',
        'target_qty',
        'target_percentage',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function monthlyTarget()
    {
        return $this->belongsTo(AdAgentMonthlyTarget::class, 'monthly_target_id');
    }

    public function item()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
