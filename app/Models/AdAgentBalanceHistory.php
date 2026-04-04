<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAgentBalanceHistory extends Model
{
    use HasFactory;

    protected $table = 'ad_agent_balance_history';

    protected $fillable = [
        'agent_id',
        'order_id',
        'previous_balance',
        'amount',
        'new_balance',
        'type',
        'description',
        'created_by',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function order()
    {
        return $this->belongsTo(StmOrderRequest::class, 'order_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
