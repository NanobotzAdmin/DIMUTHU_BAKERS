<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessInvoicePayments extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_invoice_payments';

    protected $fillable = [
        'receipt_number',
        'payment_type',
        'ad_cubusiness_has_invoice_id',
        'payment_date',
        'cheque_date',
        'cheque_number',
        'amount',
        'status',
        'created_by',
        'updated_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(AdCubusinessHasInvoice::class, 'ad_cubusiness_has_invoice_id');
    }
}
