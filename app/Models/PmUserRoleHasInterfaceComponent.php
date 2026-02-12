<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmUserRoleHasInterfaceComponent extends Model
{
    use HasFactory;

    protected $table = 'pm_user_role_has_interface_components';

    protected $fillable = [
        'pm_user_role_id',
        'pm_interface_components_id',
        'status',
        'created_by',
        'updated_by',
        'remark1',
        'remark2',
    ];
}
