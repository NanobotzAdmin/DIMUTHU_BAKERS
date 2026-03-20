<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmSuperviser extends Model
{
    use HasFactory;

    protected $table = 'sm_superviser';

    protected $fillable = [
        'user_id',
        'superviser_code',
        'superviser_name',
        'contact_number',
        'nic_number',
        'address',
        'status',
        'agent_id',
        'is_added',
    ];

    public function agent()
    {
        return $this->belongsTo(AdAgent::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
