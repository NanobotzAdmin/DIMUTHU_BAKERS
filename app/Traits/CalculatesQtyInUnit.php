<?php

namespace App\Traits;

use App\CommonVariables;
use App\Models\PmProductItem;
use App\Models\PmVariationValue;
use Illuminate\Support\Facades\Log;

trait CalculatesQtyInUnit
{
    public static function bootCalculatesQtyInUnit()
    {
        static::saving(function ($model) {
            $model->calculateQtyInUnit();
        });
    }

    public function calculateQtyInUnit()
    {
        try {
            // Determine Product Item ID
            $productItemId = $this->pm_product_item_id ?? null;

            if (!$productItemId) {
                return;
            }

            // Fetch Product Item with Variation Value
            $productItem = PmProductItem::with('variationValue')->find($productItemId);

            if (!$productItem || !$productItem->variationValue) {
                // If no variation value, maybe default to quantity itself or specific logic
                // For now, if no unit defined, we can assume unit is 1
                $this->qty_in_unit = $this->quantity ?? 1; // Default fallback
                return;
            }

            $variationValue = $productItem->variationValue;
            $unitId = $variationValue->unit_of_measurement_id;
            $unitValue = floatval($variationValue->variation_value); // e.g. 50 (kg)

            // Conversion Logic based on CommonVariables::$UnitOfMeasurement
            // '1' => 'g', '2' => 'ml', '3' => 'kg', '4' => 'l', '5' => 'piece'

            $conversionFactor = 1;

            switch ($unitId) {
                case 3: // kg -> g
                    $conversionFactor = 1000;
                    break;
                case 4: // l -> ml
                    $conversionFactor = 1000;
                    break;
                default:
                    // g, ml, piece are base units (1)
                    $conversionFactor = 1;
                    break;
            }

            // Explicit quantity handling
            // StmStock, StmBranchStock have 'quantity'
            // StmStockTransfer uses 'requesting_quantity'
            // StmBarcode might not, so we treat it as 1 unit
            $quantity = $this->quantity ?? $this->requesting_quantity ?? 1.0;

            // Calculation: (Unit Value * Conversion) * Quantity
            // Ex: 50kg bag * 2 bags = (50 * 1000) * 2 = 100,000g
            $this->qty_in_unit = ($unitValue * $conversionFactor) * $quantity;

        } catch (\Exception $e) {
            Log::error('Error calculating qty_in_unit for model ' . get_class($this) . ': ' . $e->getMessage());
            // Fallback to ensure column isn't null if strict mode is on, though default is 0
            $this->qty_in_unit = 0;
        }
    }
}
