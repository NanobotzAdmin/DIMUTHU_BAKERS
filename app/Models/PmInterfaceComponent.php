<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmInterfaceComponent extends Model
{
    use HasFactory;

    protected $table = 'pm_interface_components';

    protected $fillable = [
        'pm_interface_id',
        'components_name',
        'component_id',
        'status',
        'created_by',
        'updated_by',
        'remark1',
        'remark2',
    ];

    public function interface()
    {
        return $this->belongsTo(PmInterface::class, 'pm_interface_id');
    }
}
