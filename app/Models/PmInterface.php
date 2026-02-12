<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmInterface extends Model
{
    use HasFactory;

    protected $table = 'pm_interfaces';

    protected $fillable = [
        'pm_interface_topic_id',
        'interface_name',
        'path',
        'icon_class',
        'tile_class',
        'status',
        'created_by',
        'updated_by',
        'remark1',
        'remark2',
        'show_in_slidebar',
        'order_no',
    ];

    public function topic()
    {
        return $this->belongsTo(PmInterfaceTopic::class, 'pm_interface_topic_id');
    }

    public function components()
    {
        return $this->hasMany(PmInterfaceComponent::class, 'pm_interface_id');
    }
}
