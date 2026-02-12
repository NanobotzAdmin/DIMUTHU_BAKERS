<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'registration_number',
        'tax_id',
        'address',
        'status',
        'rating',
        'total_orders',
        'on_time_delivery',
        'quality_score',
        'lead_time',
        'credit_limit',
        'current_credit',
        'payment_terms',
        'tags',
        'categories',
        'documents',
        'contracts',
        'website',
        'bank_details',
    ];

    protected $casts = [
        'tags' => 'array',
        'categories' => 'array',
        'documents' => 'array',
        'contracts' => 'array',
        'bank_details' => 'array',
        'rating' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'current_credit' => 'decimal:2',
    ];

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class, 'supplier_id');
    }

    public function primaryContact()
    {
        return $this->hasOne(SupplierContact::class, 'supplier_id')->where('is_primary', true);
    }

    // Get products from PmProductItem that are associated with this supplier
    // This assumes there's a relationship between products and suppliers
    public function purchaseOrders()
    {
        return $this->hasMany(StmPurchaseOrder::class, 'supplier_id');
    }

    public function products()
    {
        // This relationship is established through the supplier_product_items pivot table
        return $this->belongsToMany(PmProductItem::class, 'supplier_product_items', 'supplier_id', 'product_item_id')
            ->withPivot(['unit_price', 'sku', 'category', 'unit'])
            ->withTimestamps();
    }

    public function supplierProductItems()
    {
        return $this->hasMany(SupplierProductItem::class, 'supplier_id');
    }
}
