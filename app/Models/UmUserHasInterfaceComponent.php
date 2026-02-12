<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmUserHasInterfaceComponent extends Model
{
    use HasFactory;

    protected $table = 'um_user_has_interface_components';

    protected $fillable = [
        'um_user_id',
        'pm_interface_components_id',
        'status',
        'created_by',
        'updated_by',
        'remark1',
        'remark2',
    ];
}
