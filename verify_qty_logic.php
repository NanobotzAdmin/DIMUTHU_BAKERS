<?php

use App\Models\StmStock;
use App\Models\PmProductItem;
use App\Models\PmProduct;
use App\Models\PmVariation;
use App\Models\PmVariationValue;
use Illuminate\Support\Facades\DB;

// Create dummy product data
DB::beginTransaction();
try {
    $product = PmProduct::create([
        'product_name' => 'Test Product',
        'status' => 1,
        'created_by' => 1
    ]);

    $variation = PmVariation::create([
        'variation_name' => 'Test Variation',
        'status' => 1,
        'created_by' => 1
    ]);

    // KG variation (Unit ID 3 -> 1000g)
    $varValueKg = PmVariationValue::create([
        'pm_variation_id' => $variation->id,
        'unit_of_measurement_id' => 3, // kg
        'variation_value' => '50', // 50kg
        'status' => 1,
        'created_by' => 1,
        'updated_by' => 1
    ]);

    $itemKg = PmProductItem::create([
        'pm_product_id' => $product->id,
        'pm_variation_value_id' => $varValueKg->id,
        'product_name' => '50kg Test Bag',
        'status' => 1,
        'created_by' => 1,
        'updated_by' => 1
    ]);

    // Test StmStock creation
    $stock = StmStock::create([
        'pm_product_item_id' => $itemKg->id,
        'quantity' => 2, // 2 units of 50kg
        'created_by' => 1,
        'updated_by' => 1
    ]);

    // Expected: 2 * (50 * 1000) = 100,000 g
    echo "StmStock Test:\n";
    echo "Qty: {$stock->quantity}\n";
    echo "QtyInUnit (Expected 100000.00): {$stock->qty_in_unit}\n";

    if (floatval($stock->qty_in_unit) == 100000.00) {
        echo "PASS\n";
    } else {
        echo "FAIL\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
DB::rollBack(); // Don't save junk
