<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmProduct extends Model
{
    use HasFactory;

    protected $table = 'pm_product';

    protected $fillable = [
        'product_name',
        'product_code',
        'product_description',
        'status',
        'created_by',
    ];


    public function items()
    {
        return $this->hasMany(PmProductItem::class, 'pm_product_id');
    }
}
