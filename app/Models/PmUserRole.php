<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmUserRole extends Model
{
    protected $table = 'pm_user_role';
    protected $fillable = [
        'user_role_name',
        'created_by',
        'updated_by',
        'remark1',
        'remark2',
    ];
}
