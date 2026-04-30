<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdReturnProductStock extends Model
{
    use HasFactory;

    protected $table = 'ad_return_product_stocks';

    protected $fillable = [
        'pm_product_item_id',
        'ad_daily_load_id',
        'ad_customer_has_business_id',
        'quantity',
        'unit_price',
        'reason',
        'status',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function dailyLoad()
    {
        return $this->belongsTo(AdDailyLoad::class, 'ad_daily_load_id');
    }

    public function business()
    {
        return $this->belongsTo(AdCustomerHasBusiness::class, 'ad_customer_has_business_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
