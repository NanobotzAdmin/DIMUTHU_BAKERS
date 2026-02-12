<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmStockOrderRequestHistory extends Model
{
    use HasFactory;

    protected $table = 'stm_stock_order_request_history';

    protected $fillable = [
        'order_request_id',
        'created_by',
        'action',
        'status',
        'description',
    ];

    public function orderRequest()
    {
        return $this->belongsTo(StmStockOrderRequest::class, 'order_request_id');
    }

    public function user()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
