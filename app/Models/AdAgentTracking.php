<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAgentTracking extends Model
{
    use HasFactory;

    protected $table = 'ad_agent_tracking';

    protected $fillable = [
        'agent_id',
        'lat',
        'long',
        'date',
    ];

    protected $casts = [
        'agent_id' => 'integer',
        'lat' => 'double',
        'long' => 'double',
        'date' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }
}
