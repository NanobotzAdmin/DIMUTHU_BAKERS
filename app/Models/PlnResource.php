<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlnResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'pln_department_id',
        'name',
        'type',
        'capacity',
        'status'
    ];

    public function department()
    {
        return $this->belongsTo(PlnDepartment::class, 'pln_department_id');
    }

    public function schedules()
    {
        return $this->hasMany(PlnProductionSchedule::class);
    }

    public function branches()
    {
        return $this->belongsToMany(UmBranch::class, 'um_branch_has_resources', 'pln_resource_id', 'um_branch_id');
    }
}
