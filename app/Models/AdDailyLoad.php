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
        'load_date',
        'status',
        'is_mark_as_loaded',
        'notes',
    ];

    protected $casts = [
        'load_date' => 'date',
        'status' => 'integer',
        'is_mark_as_loaded' => 'boolean',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function route()
    {
        return $this->belongsTo(AdRoute::class, 'route_id');
    }

    public function items()
    {
        return $this->hasMany(AdDailyLoadItem::class, 'daily_load_id');
    }
}
