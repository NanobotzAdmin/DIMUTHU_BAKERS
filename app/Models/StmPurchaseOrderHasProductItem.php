<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmPurchaseOrderHasProductItem extends Model
{
    use HasFactory;

    protected $table = 'stm_purchase_order_has_product_items';

    protected $fillable = [
        'purchase_order_id',
        'product_item_id',
        'unit_price',
        'quantity',
        'created_by',
        'updated_by',
        'grn_received_quantity',
        'is_completed',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(StmPurchaseOrder::class, 'purchase_order_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'product_item_id');
    }
}
