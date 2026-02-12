<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\CalculatesQtyInUnit;

class StmStockTransfer extends Model
{
    use HasFactory, CalculatesQtyInUnit;

    protected $table = 'stm_stock_transfer';

    protected $fillable = [
        'pm_product_item_id',
        'stm_stock_order_request_id',
        'stm_stock_id',
        'batch_number',
        'requesting_quantity',
        'qty_in_unit',
        'approved_quantity',
        'approved_date',
        'approved_by',
        'dispatched_quantity',
        'dispatched_date',
        'dispatched_by',
        'received_quantity',
        'received_date',
        'received_by',
        'received_by',
        'branch_stock_id',
    ];

    public function orderRequest()
    {
        return $this->belongsTo(StmStockOrderRequest::class, 'stm_stock_order_request_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function stock()
    {
        return $this->belongsTo(StmStock::class, 'stm_stock_id');
    }

    public function branchStock()
    {
        return $this->belongsTo(StmBranchStock::class, 'branch_stock_id');
    }
}
