<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAgent extends Model
{
    use HasFactory;

    protected $table = 'ad_agent';

    protected $fillable = [
        'user_id',
        'agent_code',
        'agent_name',
        'agent_type',
        'status',
        'phone',
        'email',
        'nic_number',
        'address',
        'base_salary',
        'commission_rate',
        'credit_limit',
        'credit_period_days',
        'outstanding_balance',
        'total_sales',
        'total_collections',
    ];

    protected $casts = [
        'agent_type' => 'integer',
        'status' => 'integer',
        'base_salary' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'credit_period_days' => 'integer',
        'outstanding_balance' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_collections' => 'decimal:2',
    ];

    /**
     * Boot method to auto-generate agent code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($agent) {
            if (empty($agent->agent_code)) {
                $agent->agent_code = self::generateAgentCode();
            }
        });
    }

    /**
     * Generate unique agent code in format: AG-YYYY-NNNN
     */
    public static function generateAgentCode()
    {
        $year = date('Y');
        $prefix = "AG-{$year}-";

        // Get the last agent code for this year
        $lastAgent = self::where('agent_code', 'like', "{$prefix}%")
            ->orderBy('agent_code', 'desc')
            ->first();

        if ($lastAgent) {
            // Extract number from last code and increment
            $lastNumber = (int) substr($lastAgent->agent_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix.str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get all bank accounts for this agent
     */
    public function bankAccounts()
    {
        return $this->hasMany(AdAgentHasBankAccount::class, 'agent_id');
    }

    /**
     * Get the primary bank account
     */
    public function primaryBankAccount()
    {
        return $this->hasOne(AdAgentHasBankAccount::class, 'agent_id')
            ->where('is_primary', true);
    }

    /**
     * Get the associated user account
     */
    public function user()
    {
        return $this->belongsTo(UmUser::class, 'user_id');
    }
}
