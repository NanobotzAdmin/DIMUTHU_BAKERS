<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmSupervisorTracking extends Model
{
    use HasFactory;

    protected $table = 'sm_supervisor_tracking';

    protected $fillable = [
        'superviser_id',
        'agent_id',
        'lat',
        'long',
        'date',
    ];

    protected $casts = [
        'superviser_id' => 'integer',
        'agent_id' => 'integer',
        'lat' => 'double',
        'long' => 'double',
        'date' => 'datetime',
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
