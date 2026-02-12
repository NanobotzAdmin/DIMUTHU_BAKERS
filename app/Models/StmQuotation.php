<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmQuotation extends Model
{
    use HasFactory;

    protected $table = 'stm_quotation';

    protected $fillable = [
        'quotation_number',
        'customer_id',
        'quotation_date',
        'valid_until',
        'status',
        'grand_total',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quotation_date' => 'date',
        'valid_until' => 'date',
        'status' => 'integer',
        'grand_total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(CmCustomer::class, 'customer_id');
    }

    public function products()
    {
        return $this->hasMany(StmQuotationHasProduct::class, 'stm_quotation_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }
}
