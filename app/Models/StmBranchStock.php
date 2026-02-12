<?php

namespace App\Models;

use App\Traits\CalculatesQtyInUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmBranchStock extends Model
{
    use CalculatesQtyInUnit, HasFactory;

    protected $table = 'stm_branch_stock';

    protected $fillable = [
        'pm_product_item_id',
        'qty_in_unit',
        'um_branch_id',
        'pln_department_id',
        'stm_stock_id',
        'stm_stock_transfer_id',
        'stm_order_request_has_product_id',
        'agent_id',
        'quantity',
        'status',
        'created_by',
        'updated_by',
    ];

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function branch()
    {
        return $this->belongsTo(UmBranch::class, 'um_branch_id');
    }

    public function stock()
    {
        return $this->belongsTo(StmStock::class, 'stm_stock_id');
    }

    public function orderRequestProduct()
    {
        return $this->belongsTo(StmOrderRequestHasProduct::class, 'stm_order_request_has_product_id');
    }

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }
}
