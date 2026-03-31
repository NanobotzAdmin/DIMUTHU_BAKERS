<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAgentMonthlyTarget extends Model
{
    protected $table = 'ad_agent_has_monthly_targets';
    
    protected $casts = [
        'target_year' => 'integer',
        'target_month' => 'integer',
        'monthly_sales_target' => 'decimal:2',
        'base_salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'invoicing_commission_rate' => 'decimal:2',
        'target_commission_rate' => 'decimal:2',
        'achievement_threshold' => 'decimal:2',
        'reduced_target_commission_rate' => 'decimal:2',
        'monthly_commission' => 'decimal:2',
        'payment_status' => 'integer',
        'status' => 'integer',
    ];

    protected $fillable = [
        'agent_id',
        'target_year',
        'target_month',
        'monthly_sales_target',
        'base_salary',
        'commission_rate',
        'invoicing_commission_rate',
        'target_commission_rate',
        'achievement_threshold',
        'reduced_target_commission_rate',
        'monthly_commission',
        'payment_status',
        'status',
        'created_by',
        'updated_by',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function categoryTargets()
    {
        return $this->hasMany(AdAgentHasCategoryTargets::class, 'monthly_target_id');
    }

    public function itemTargets()
    {
        return $this->hasMany(AdAgentHasItemTargets::class, 'monthly_target_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo(UmUser::class, 'updated_by');
    }
}
