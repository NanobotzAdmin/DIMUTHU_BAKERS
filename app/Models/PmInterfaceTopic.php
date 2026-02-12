<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmInterfaceTopic extends Model
{
    use HasFactory;

    protected $table = 'pm_interface_topic';

    protected $fillable = [
        'topic_name',
        'menu_icon',
        'section_class',
        'status',
        'created_by',
        'updated_by',
        'remark1',
        'remark2',
        'show_in_slidebar',
        'order_no',
    ];

    public function interfaces()
    {
        return $this->hasMany(PmInterface::class, 'pm_interface_topic_id');
    }
}
