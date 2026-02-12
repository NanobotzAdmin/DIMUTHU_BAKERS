<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierProductItem extends Model
{
    protected $table = 'supplier_product_items';

    protected $fillable = [
        'supplier_id',
        'product_item_id',
        'unit_price',
        'minimum_order',
        'sku',
        'category',
        'unit',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'product_item_id');
    }
}
