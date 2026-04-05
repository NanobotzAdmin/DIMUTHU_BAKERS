<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VmVehicle extends Model
{
    use HasFactory;

    protected $table = 'vm_vehicle';

    protected $fillable = [
        'agent_id',
        'vehicle_number',
        'engine_number',
        'chassis_number',
        'vehicle_image',
        'status',
        'is_added',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }
}
