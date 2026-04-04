<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmOrderRequestHasPayment extends Model
{
    use HasFactory;

    protected $table = 'stm_order_request_has_payments';

    protected $fillable = [
        'stm_order_request_id',
        'ad_agent_payment_id',
        'payment_amount',
        'payment_method',
        'payment_reference',
        'payment_date',
        'status',
        'notes',
        'created_by',
    ];

    public function agentPayment()
    {
        return $this->belongsTo(AdAgentPayment::class, 'ad_agent_payment_id');
    }

    protected $casts = [
        'payment_date' => 'datetime',
        'payment_amount' => 'decimal:2',
    ];

    public function orderRequest()
    {
        return $this->belongsTo(StmOrderRequest::class, 'stm_order_request_id');
    }
}
