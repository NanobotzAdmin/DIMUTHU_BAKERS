<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StmQuotationHasProduct extends Model
{
    use HasFactory;

    protected $table = 'stm_quotation_has_products';

    protected $fillable = [
        'stm_quotation_id',
        'pm_product_item_id',
        'quantity',
        'unit_price',
        'subtotal',
        'notes',
    ];

    public function quotation()
    {
        return $this->belongsTo(StmQuotation::class, 'stm_quotation_id');
    }

    public function productItem()
    {
        return $this->belongsTo(PmProductItem::class, 'pm_product_item_id');
    }
}
