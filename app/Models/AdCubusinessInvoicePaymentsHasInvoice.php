<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessInvoicePaymentsHasInvoice extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_invoice_payments_has_invoices';

    protected $fillable = [
        'ad_cubusiness_invoice_payments_id',
        'ad_cubusiness_has_invoice_id',
        'amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function masterPayment()
    {
        return $this->belongsTo(AdCubusinessInvoicePayments::class, 'ad_cubusiness_invoice_payments_id');
    }

    public function invoice()
    {
        return $this->belongsTo(AdCubusinessHasInvoice::class, 'ad_cubusiness_has_invoice_id');
    }
}
