<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdDailyLoadItem extends Model
{
    use HasFactory;

    protected $table = 'ad_daily_loads_has_product_items';

    protected $fillable = [
        'daily_load_id',
        'product_item_id',
        'stm_branch_stock_id',
        'loaded_qty',
        'available_quantity',
        'unload_qty',
        'price',
        'total_value',
    ];

    protected $casts = [
        'loaded_qty' => 'decimal:3',
        'available_quantity' => 'decimal:3',
        'price' => 'decimal:2',
        'total_value' => 'decimal:2',
    ];

    public function dailyLoad()
    {
        return $this->belongsTo(AdDailyLoad::class, 'daily_load_id');
    }

    public function product()
    {
        return $this->belongsTo(PmProductItem::class, 'product_item_id');
    }
}
