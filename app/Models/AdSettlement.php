<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdSettlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'route_id',
        'daily_load_id',
        'settlement_number',
        'settlement_date',
        'total_sales',
        'cash_sales',
        'credit_sales',
        'cheque_sales',
        'commission_earned',
        'status',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function route()
    {
        return $this->belongsTo(AdRoute::class, 'route_id');
    }

    public function dailyLoad()
    {
        return $this->belongsTo(AdDailyLoad::class, 'daily_load_id');
    }
}
