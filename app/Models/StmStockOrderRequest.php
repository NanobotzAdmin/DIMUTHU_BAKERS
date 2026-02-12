<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmStockOrderRequest extends Model
{
    use HasFactory;

    protected $table = 'stm_stock_order_request';

    protected $fillable = [
        'order_number',
        'um_branch_id',
        'pln_department_id',
        'is_active',
        'req_from_branch_id',
        'req_from_department_id',
        'status',
        'priority_level',
        'notes',
        'scheduled_date',
        'created_by',
        'updated_by',
    ];

    public function requestingBranch()
    {
        return $this->belongsTo(UmBranch::class, 'um_branch_id');
    }

    public function supplyingBranch()
    {
        return $this->belongsTo(UmBranch::class, 'req_from_branch_id');
    }

    public function transfers()
    {
        return $this->hasMany(StmStockTransfer::class, 'stm_stock_order_request_id');
    }

    public function history()
    {
        return $this->hasMany(StmStockOrderRequestHistory::class, 'order_request_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
