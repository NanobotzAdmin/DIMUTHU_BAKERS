<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmProductItemHasProductType extends Model
{
    use HasFactory;

    protected $table = 'pm_product_item_has_product_types';

    protected $fillable = [
        'product_item_id',
        'product_type_id',
    ];

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'product_item_id');
    }

    public function productType()
    {
        return $this->belongsTo(PmProductType::class, 'product_type_id');
    }
}
