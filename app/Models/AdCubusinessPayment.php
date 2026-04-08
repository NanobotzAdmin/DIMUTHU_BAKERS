<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessPayment extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_payments';

    protected $fillable = [
        'receipt_number',
        'payment_type',
        'ad_customer_has_business_id',
        'payment_date',
        'cheque_date',
        'cheque_number',
        'amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function business()
    {
        return $this->belongsTo(AdCustomerHasBusiness::class, 'ad_customer_has_business_id');
    }

    public function items()
    {
        return $this->hasMany(AdCubusinessPaymentHasInvoice::class, 'ad_cubusiness_payment_id');
    }
}
