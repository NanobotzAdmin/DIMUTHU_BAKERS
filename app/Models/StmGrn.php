<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StmPurchaseOrder;

class StmGrn extends Model
{
    use HasFactory;

    protected $table = 'stm_grn';

    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'grn_number',
        'invoice_number',
        'invoice_amount',
        'notes',
        'is_completed',
        'is_active',
        'created_by',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(StmPurchaseOrder::class, 'purchase_order_id');
    }

    public function stockIns()
    {
        return $this->hasMany(StmStockIn::class, 'stm_grn_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
}
