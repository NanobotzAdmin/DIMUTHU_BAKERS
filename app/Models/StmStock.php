<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculatesQtyInUnit;

class StmStock extends Model
{
    use HasFactory, CalculatesQtyInUnit;

    protected $table = 'stm_stock';

    protected $fillable = [
        'stm_stock_in_id',
        'pm_product_item_id',
        'qty_in_unit',
        'stock_date',
        'quantity',
        'costing_price',
        'selling_price',
        'notes',
        'manufacturing_date',
        'expiry_date',
        'expire_period',
        'quality_check',
        'batch_number',
        'rack_number',
        'created_by',
        'updated_by',
    ];

    public function stockIn()
    {
        return $this->belongsTo(StmStockIn::class, 'stm_stock_in_id');
    }

    public function barcodes()
    {
        return $this->hasMany(StmBarcode::class, 'stm_stock_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
