<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAgentPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'ad_agent_payment_histories';

    protected $fillable = [
        'ad_agent_payment_id',
        'created_by',
        'action',
        'status',
        'description',
    ];

    /**
     * Get the payment associated with the history log.
     */
    public function payment()
    {
        return $this->belongsTo(AdAgentPayment::class, 'ad_agent_payment_id');
    }

    /**
     * Get the user who made the change.
     */
    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
