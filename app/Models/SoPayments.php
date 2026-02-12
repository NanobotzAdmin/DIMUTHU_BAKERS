<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoPayments extends Model
{
    use HasFactory;

    protected $table = 'so_payments';

    protected $fillable = [
        'so_invoice_id',
        'paid_amount',
        'payment_type',
        'card_4_digits',
        'transaction_id',
        'reference',
        'gift_card_code',
        'status',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];
}
