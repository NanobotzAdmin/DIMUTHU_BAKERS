<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoInvoice extends Model
{
    use HasFactory;

    protected $table = 'so_invoice';

    protected $fillable = [
        'invoice_number',
        'total_price',
        'tax_amount',
        'discount_type',
        'discount_value',
        'payble_amount',
        'given_amount',
        'um_branch_id',
        'cm_customer_id',
        'created_by',
        'status',
        'created_at',
        'updated_at'
    ];

    public function items()
    {
        return $this->hasMany(SoInvoiceHasStock::class, 'so_invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(SoPayments::class, 'so_invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(CmCustomer::class, 'cm_customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
