<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmSupervisorTracking extends Model
{
    use HasFactory;

    protected $table = 'sm_supervisor_tracking';

    public const TYPE_PERIODIC_30MIN = 1;
    public const TYPE_START_TRIP = 2;
    public const TYPE_CUSTOMER_BILLING = 3;
    public const TYPE_UNLOAD = 4;
    public const TYPE_LIVE_TRACKING = 5;

    protected $fillable = [
        'superviser_id',
        'agent_id',
        'lat',
        'long',
        'date',
        'description',
        'tracking_type',
    ];

    protected $casts = [
        'superviser_id' => 'integer',
        'agent_id' => 'integer',
        'lat' => 'double',
        'long' => 'double',
        'date' => 'datetime',
        'tracking_type' => 'integer',
    ];

    public function supervisor()
    {
        return $this->belongsTo(SmSuperviser::class, 'superviser_id');
    }

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }
}
