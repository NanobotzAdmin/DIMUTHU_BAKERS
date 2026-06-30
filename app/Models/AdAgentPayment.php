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
        'created_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'status' => 'integer',
        'payment_method' => 'integer',
        'rejected_at' => 'datetime',
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

    /**
     * Get the user who rejected the payment.
     */
    public function rejectedByUser()
    {
        return $this->belongsTo(UmUser::class, 'rejected_by');
    }

    /**
     * Get the credit notes associated with this payment.
     */
    public function creditNotes()
    {
        return $this->hasMany(AdCreditNote::class, 'ad_agent_payment_id');
    }
    
    /**
     * Get the history records for this payment.
     */
    public function history()
    {
        return $this->hasMany(AdAgentPaymentHistory::class, 'ad_agent_payment_id');
    }
}
