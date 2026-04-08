<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessHasReturnProductItem extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_has_return_product_item';

    protected $fillable = [
        'ad_cubusiness_has_invoice_id', // Stores Previous Invoice ID as per user request
        'ad_new_invoice_id', // Stores the Current Invoice ID
        'pm_product_item_id',
        'stm_branch_stock_id',
        'return_quantity',
        'unit_price',
        'total_price',
        'reason',
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
}
