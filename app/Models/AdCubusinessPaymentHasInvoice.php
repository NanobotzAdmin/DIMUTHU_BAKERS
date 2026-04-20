<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessPaymentHasInvoice extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_payment_has_invoices';

    protected $fillable = [
        'ad_cubusiness_payment_id',
        'ad_cubusiness_has_invoice_id',
        'amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function payment()
    {
        return $this->belongsTo(AdCubusinessPayment::class, 'ad_cubusiness_payment_id');
    }

    public function invoice()
    {
        return $this->belongsTo(AdCubusinessHasInvoice::class, 'ad_cubusiness_has_invoice_id');
    }
}
