<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmStockIn extends Model
{
    use HasFactory;

    protected $table = 'stm_stock_in';

    protected $fillable = [
        'stm_grn_id',
        'pm_product_item_id',
        'added_quantity',
        'costing_price',
        'selling_price',
        'notes',
        'manufacturing_date',
        'expiry_date',
        'expire_period',
        'quality_check',
        'batch_number',
        'created_by',
        'updated_by',
    ];

    public function grn()
    {
        return $this->belongsTo(StmGrn::class, 'stm_grn_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function stock()
    {
        return $this->hasOne(StmStock::class, 'stm_stock_in_id');
    }


}
