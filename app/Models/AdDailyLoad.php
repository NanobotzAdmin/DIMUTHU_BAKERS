<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdDailyLoad extends Model
{
    use HasFactory;

    protected $table = 'ad_daily_loads';

    protected $fillable = [
        'agent_id',
        'route_id',
        'vehicle_id',
        'driver_id',
        'supervisor_id',
        'starting_mileage',
        'ending_mileage',
        'unload_time',
        'load_date',
        'status',
        'load_status',
        'is_mark_as_loaded',
        'notes',
    ];

    protected $casts = [
        'load_date' => 'date',
        'status' => 'integer',
        'load_status' => 'integer',
        'is_mark_as_loaded' => 'boolean',
        'starting_mileage' => 'float',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function route()
    {
        return $this->belongsTo(AdRoute::class, 'route_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(SmSuperviser::class, 'supervisor_id');
    }

    public function driver()
    {
        return $this->belongsTo(DmDriver::class, 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(VmVehicle::class, 'vehicle_id');
    }

    public function items()
    {
        return $this->hasMany(AdDailyLoadItem::class, 'daily_load_id');
    }

    public function invoices()
    {
        return $this->hasMany(AdCubusinessHasInvoice::class, 'ad_daily_load_id');
    }
}
