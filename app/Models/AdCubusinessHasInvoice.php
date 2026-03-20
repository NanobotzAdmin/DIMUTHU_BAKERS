<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdCubusinessHasInvoice extends Model
{
    use HasFactory;

    protected $table = 'ad_cubusiness_has_invoice';

    const PAYMENT_STATUS_PENDING = 0;
    const PAYMENT_STATUS_PARTIAL = 1;
    const PAYMENT_STATUS_COMPLETE = 2;

    protected $fillable = [
        'ad_customer_has_business_id',
        'ad_daily_load_id',
        'invoice_number',
        'inoice_type',
        'invoice_price',
        'net_price',
        'return_price',
        'total_amount_paid',
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
        return $this->hasMany(AdCubusinessHasProductItem::class, 'ad_cubusiness_has_invoice_id');
    }

    public function returnItems()
    {
        return $this->hasMany(AdCubusinessHasReturnProductItem::class, 'ad_cubusiness_has_invoice_id');
    }

    public function newReturnItems()
    {
        return $this->hasMany(AdCubusinessHasReturnProductItem::class, 'ad_new_invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(AdCubusinessInvoicePayments::class, 'ad_cubusiness_has_invoice_id');
    }
}
