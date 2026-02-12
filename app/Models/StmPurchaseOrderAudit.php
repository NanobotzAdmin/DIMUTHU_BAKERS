<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UmUser;

class StmPurchaseOrderAudit extends Model
{
    use HasFactory;

    protected $table = 'stm_purchase_order_audit';

    protected $fillable = [
        'purchase_order_id',
        'user_id',
        'user_role',
        'action',
        'description',
        'previous_status',
        'new_status',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(StmPurchaseOrder::class, 'purchase_order_id');
    }

    public function user()
    {
        return $this->belongsTo(UmUser::class, 'user_id');
    }
}
