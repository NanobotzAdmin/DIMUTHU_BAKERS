<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAgentPayment extends Model
{
    use HasFactory;

    protected $table = 'ad_agent_payments';

    protected $fillable = [
        'agent_id',
        'amount',
        'payment_method',
        'payment_date',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'status' => 'integer',
        'payment_method' => 'integer',
    ];

    /**
     * Get the agent who made the payment.
     */
    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    /**
     * Get the order request payments associated with this agent payment.
     */
    public function distributions()
    {
        return $this->hasMany(StmOrderRequestHasPayment::class, 'ad_agent_payment_id');
    }

    /**
     * Get the user who created the record.
     */
    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
    
}
