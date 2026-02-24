<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmOrderRequestHistory extends Model
{
    use HasFactory;

    protected $table = 'stm_order_request_history';

    protected $fillable = [
        'order_request_id',
        'created_by',
        'action',
        'status',
        'description',
    ];

    public function orderRequest()
    {
        return $this->belongsTo(StmOrderRequest::class, 'order_request_id');
    }

    public function user()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
