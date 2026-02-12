<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmOrderRequestHasProduct extends Model
{
    use HasFactory;

    protected $table = 'stm_order_requests_has_product';

    protected $fillable = [
        'stm_order_request_id',
        'pm_product_item_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function orderRequest()
    {
        return $this->belongsTo(StmOrderRequest::class, 'stm_order_request_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
