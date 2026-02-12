<?php

namespace App\Models;

use App\Traits\CalculatesQtyInUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmBarcode extends Model
{
    use CalculatesQtyInUnit, HasFactory;

    protected $table = 'stm_barcodes';

    protected $fillable = [
        'barcode',
        'qty_in_unit',
        'stm_stock_id',
        'pm_product_item_id',
        'selling_price',
        'is_sold',
        'created_by',
        'stm_stock_order_request_id',
        'stm_order_requests_id',
        'um_branch_id',
        'pln_department_id',
        'agent_id',
    ];

    public function stock()
    {
        return $this->belongsTo(StmStock::class, 'stm_stock_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function history()
    {
        return $this->hasMany(StmBarcodesHistory::class, 'barcode_id');
    }

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }
}
