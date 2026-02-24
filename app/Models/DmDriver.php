<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DmDriver extends Model
{
    use HasFactory;

    protected $table = 'dm_driver';

    protected $fillable = [
        'agent_id',
        'user_id',
        'driver_name',
        'licence_number',
        'licences_expire_date',
        'contact_number',
        'status',
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
