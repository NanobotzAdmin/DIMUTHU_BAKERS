<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCustomerHasBusiness extends Model
{
    use HasFactory;

    protected $table = 'ad_customer_has_business';

    protected $fillable = [
        'customer_id',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'b2b_customer_type',
        'payment_terms',
        'visit_schedule',
        'preferred_visit_days',
        'credit_limit',
        'payment_terms_days',
        'agent_id',
        'route_id',
        'stop_sequence',
        'allow_credit',
        'preferred_time',
        'special_instructions',
        'delivery_instructions',
        'notes',
    ];

    protected $casts = [
        'preferred_visit_days' => 'array',
        'credit_limit' => 'decimal:2',
        'allow_credit' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(CmCustomer::class, 'customer_id');
    }
}
