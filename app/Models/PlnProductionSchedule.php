<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlnProductionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'pln_resource_id',
        'stm_order_request_id',
        'pm_product_item_id',
        'start_time',
        'end_time',
        'quantity',
        'status',
        'notes',
        'created_by',
        'user_id',
        'um_branch_id',
        'pln_department_id',
        'actual_output',
        'waste_reason',
        'quality_note',
        'quality_photo_path',
        'is_waste'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(PlnResource::class, 'pln_resource_id');
    }

    public function orderRequest()
    {
        return $this->belongsTo(StmOrderRequest::class, 'stm_order_request_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }

    public function user()
    {
        return $this->belongsTo(UmUser::class, 'user_id');
    }

    public function scheduleInstructions()
    {
        return $this->hasMany(PlnScheduleInstruction::class, 'production_schedule_id');
    }
}
