<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdDailyLoadHasCustomer extends Model
{
    use HasFactory;

    protected $table = 'ad_daily_loads_has_customers';

    protected $fillable = [
        'daily_load_id',
        'ad_customer_has_business_id',
        'stop_sequence',
        'status',
        'distance_km',
    ];

    public function dailyLoad()
    {
        return $this->belongsTo(AdDailyLoad::class, 'daily_load_id');
    }

    public function business()
    {
        return $this->belongsTo(AdCustomerHasBusiness::class, 'ad_customer_has_business_id');
    }
}
