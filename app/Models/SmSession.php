<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmSession extends Model
{
    use HasFactory;

    protected $table = 'sm_session';

    protected $fillable = [
        'um_user_id',
        'ip_address',
        'time_in',
        'time_out',
        'is_active',
        'created_at',
        'updated_at'
    ];
}
