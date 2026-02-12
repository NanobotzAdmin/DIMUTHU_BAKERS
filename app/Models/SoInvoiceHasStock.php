<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoInvoiceHasStock extends Model
{
    use HasFactory;

    protected $table = 'so_invoice_has_stock';

    protected $fillable = [
        'so_invoice_id',
        'pm_product_item_id',
        'stm_branch_stock_id',
        'um_branch_id',
        'stock_date',
        'grn_price',
        'selling_price',
        'invoiced_price',
        'qty',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
