<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmProductType extends Model
{
    use HasFactory;

    protected $table = 'pm_product_type';

    protected $fillable = [
        'product_type_name',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

    public function productItems()
    {
        return $this->belongsToMany(PmProductItem::class, 'pm_product_item_has_product_types', 'product_type_id', 'product_item_id');
    }
}
