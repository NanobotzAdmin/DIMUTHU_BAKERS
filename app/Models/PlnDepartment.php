<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlnDepartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'color',
        'icon',
        'status'
    ];

    public function resources()
    {
        return $this->hasMany(PlnResource::class);
    }

    public function branches()
    {
        return $this->belongsToMany(UmBranch::class, 'um_branch_has_department', 'department_id', 'um_branch_id');
    }
}
