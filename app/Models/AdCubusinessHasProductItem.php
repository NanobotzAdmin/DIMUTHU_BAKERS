<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessHasProductItem extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_has_product_item';

    protected $fillable = [
        'ad_cubusiness_has_invoice_id',
        'pm_product_item_id',
        'stm_branch_stock_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'created_by',
        'updated_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(AdCubusinessHasInvoice::class, 'ad_cubusiness_has_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function product_item()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
