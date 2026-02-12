<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdAgent;

class AdAgentHasBankAccount extends Model
{
    use HasFactory;

    protected $table = 'ad_agent_has_bank_accounts';

    protected $fillable = [
        'agent_id',
        'bank_name',
        'account_number',
        'branch',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relationship back to agent
     */
    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }
}
