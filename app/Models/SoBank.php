<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoBank extends Model
{
    use HasFactory;

    protected $table = 'so_banks';

    protected $fillable = [
        'bank_name',
        'bank_code',
        'is_active',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationship to agent bank accounts
     */
    public function agentBankAccounts()
    {
        return $this->hasMany(AdAgentHasBankAccount::class, 'bank_id');
    }
}
