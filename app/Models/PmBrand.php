<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmBrand extends Model
{
    use HasFactory;

    protected $table = 'pm_brands';

    protected $fillable = [
        'brand_name',
        'brand_code',
        'status',
        'created_by'
    ];
}
