<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCreditNote extends Model
{
    use HasFactory;

    protected $table = 'ad_credit_notes';

    protected $fillable = [
        'agent_id',
        'credit_note_number',
        'credit_note_date',
        'note_type',
        'total_amount',
        'status',
        'reject_reason',
        'is_credit_use',
        'ad_customer_has_business_id',
        'reason',
        'ad_agent_payment_id',
        'created_by',
        'updated_by',
        'rejected_by',
        'rejected_at',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function business()
    {
        return $this->belongsTo(AdCustomerHasBusiness::class, 'ad_customer_has_business_id');
    }

    public function payment()
    {
        return $this->belongsTo(AdAgentPayment::class, 'ad_agent_payment_id');
    }

    public function products()
    {
        return $this->hasMany(AdCreditNoteHasProduct::class, 'credit_note_id');
    }

    public function histories()
    {
        return $this->hasMany(AdCreditNoteHistory::class, 'ad_credit_note_id')->orderBy('created_at', 'desc');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }

    public function rejectedByUser()
    {
        return $this->belongsTo(UmUser::class, 'rejected_by');
    }
}
