<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PmProductItem extends Model
{
    use HasFactory;

    protected $table = 'pm_product_item';

    protected $fillable = [
        'pm_product_id',
        'pm_brands_id',
        'pm_variation_id',
        'pm_variation_value_id',
        'product_name',
        'bin_code',
        'selling_price',
        'ref_number_auto',
        'reference_number',
        'status',
        'created_by',
        'updated_by',
    ];

    public function product()
    {
        return $this->belongsTo(PmProduct::class, 'pm_product_id');
    }

    public function brand()
    {
        return $this->belongsTo(PmBrand::class, 'pm_brands_id');
    }

    public function variation()
    {
        return $this->belongsTo(PmVariation::class, 'pm_variation_id');
    }

    public function variationValue()
    {
        return $this->belongsTo(PmVariationValue::class, 'pm_variation_value_id');
    }

    public function productTypes()
    {
        return $this->belongsToMany(PmProductType::class, 'pm_product_item_has_product_types', 'product_item_id', 'product_type_id');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'supplier_product_items', 'product_item_id', 'supplier_id')
            ->withPivot(['unit_price', 'sku', 'category', 'unit'])
            ->withTimestamps();
    }

    public function supplierProductItems()
    {
        return $this->hasMany(SupplierProductItem::class, 'product_item_id');
    }

    public function recipe()
    {
        return $this->hasOne(Recipe::class, 'product_item_id');
    }

    public function stocks()
    {
        return $this->hasMany(StmStock::class, 'pm_product_item_id');
    }

    public function branchStocks()
    {
        return $this->hasMany(StmBranchStock::class, 'pm_product_item_id');
    }
}
