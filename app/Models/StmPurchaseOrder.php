<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmPurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'stm_purchase_order';

    protected $fillable = [
        'supplier_id',
        'payment_terms',
        'delivery_date',
        'notes',
        'po_number',
        'status',
        'created_by',
        'updated_by',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(StmPurchaseOrderHasProductItem::class, 'purchase_order_id');
    }

    public function auditTrails()
    {
        return $this->hasMany(StmPurchaseOrderAudit::class, 'purchase_order_id')->orderBy('created_at', 'asc');
    }
}
