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
        'created_by',
        'updated_by',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function business()
    {
        return $this->belongsTo(AdCustomerHasBusiness::class, 'ad_customer_has_business_id');
    }

    public function products()
    {
        return $this->hasMany(AdCreditNoteHasProduct::class, 'credit_note_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
