<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmBranch extends Model
{
    use HasFactory;

    protected $table = 'um_branch';

    protected $fillable = [
        'um_branch_type_id',
        'name',
        'code',
        'street_address',
        'city',
        'province',
        'contact_person',
        'contact_person_phone',
        'cash_account',
        'bank_account',
        'status',
        'created_by',
        'updated_by',
        'is_default',
    ];

    public function type()
    {
        return $this->belongsTo(UmBranchType::class, 'um_branch_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(UmUser::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(UmUser::class, 'updated_by');
    }

    public function departments()
    {
        return $this->belongsToMany(PlnDepartment::class, 'um_branch_has_department', 'um_branch_id', 'department_id');
    }

    public function resources()
    {
        return $this->belongsToMany(PlnResource::class, 'um_branch_has_resources', 'um_branch_id', 'pln_resource_id')
            ->withPivot('pln_department_id', 'is_active', 'created_by');
    }
}
