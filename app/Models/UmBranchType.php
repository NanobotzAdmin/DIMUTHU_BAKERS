<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmBranchType extends Model
{
    use HasFactory;

    protected $table = 'um_branch_type';

    protected $fillable = [
        'name',
        'icon',
        'status',
    ];

    public function branches()
    {
        return $this->hasMany(UmBranch::class, 'um_branch_type_id');
    }
}
