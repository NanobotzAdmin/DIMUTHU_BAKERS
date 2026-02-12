<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmOrderRequest extends Model
{
    use HasFactory;

    protected $table = 'stm_order_requests';

    protected $fillable = [
        'order_number',
        'branch_id',
        'customer_id',
        'agent_id',
        'quotation_id',
        'order_type',
        'event_type',
        'guest_count',
        'delivery_type',
        'delivery_date',
        'end_time',
        'recurrence_pattern',
        'req_from_branch_id',
        'end_date',
        'payment_details',
        'payment_reference',
        'paid_amount',
        'status',
        'grand_total',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'end_time' => 'datetime',
        'end_date' => 'date',
        'delivery_date' => 'datetime',
        'grand_total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(CmCustomer::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function products()
    {
        return $this->belongsToMany(PmProductItem::class, 'stm_order_requests_has_product', 'stm_order_request_id', 'pm_product_item_id')
            ->withPivot(['quantity', 'unit_price', 'subtotal'])
            ->withTimestamps();
    }

    public function orderProducts()
    {
        return $this->hasMany(StmOrderRequestHasProduct::class, 'stm_order_request_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(UmUser::class, 'updated_by');
    }
}
