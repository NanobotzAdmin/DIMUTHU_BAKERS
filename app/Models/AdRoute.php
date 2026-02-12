<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdRoute extends Model
{
    use HasFactory;

    protected $table = 'ad_routes';

    protected $fillable = [
        'route_code',
        'route_name',
        'description',
        'target_distance_km',
        'target_duration_hours',
        'agent_id',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'agent_id' => 'integer',
        'target_distance_km' => 'float',
        'target_duration_hours' => 'float',
    ];

    /**
     * Boot method to auto-generate route code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($route) {
            if (empty($route->route_code)) {
                $route->route_code = self::generateRouteCode();
            }
        });
    }

    /**
     * Generate unique route code in format: RT-YYYY-NNNN
     */
    private static function generateRouteCode()
    {
        $year = date('Y');
        $prefix = 'RT-'.$year.'-';

        // Get the last route code for this year
        $lastRoute = self::where('route_code', 'like', $prefix.'%')
            ->orderBy('route_code', 'desc')
            ->first();

        if ($lastRoute) {
            // Extract the number part and increment
            $lastNumber = (int) substr($lastRoute->route_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // First route of the year
            $newNumber = 1;
        }

        // Format with leading zeros
        return $prefix.str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get the assigned agent
     */
    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    /**
     * Get the customers assigned to this route
     */
    public function customers()
    {
        return $this->belongsToMany(CmCustomer::class, 'ad_route_has_customers', 'route_id', 'customer_id')
            ->withPivot('stop_sequence', 'distance_km')
            ->withTimestamps();
    }
}
