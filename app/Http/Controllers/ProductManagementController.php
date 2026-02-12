<?php

namespace App\Http\Controllers;

use App\CommonVariables;
use App\Models\PmBrand;
use App\Models\PmProduct;
use App\Models\PmProductItem;
use App\Models\PmProductType;
use App\Models\PmVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductManagementController extends Controller
{
    public function storeProduct(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:150',
            'items' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            $product = null;

            // If an existing_product_id is provided, find that product. Otherwise, create a new one.
            if ($request->has('existing_product_id') && $request->existing_product_id) {
                $product = PmProduct::findOrFail($request->existing_product_id);
                // Optionally update the main product details
                $product->update([
                    'product_name' => $request->name,
                    'product_description' => $request->description,
                    'updated_by' => auth()->id() ?? 1,
                ]);
            } else {
                $product = PmProduct::create([
                    //   'product_type_id' => $request->type,
                    'product_name' => $request->name,
                    'product_description' => $request->description,
                    'status' => 1,
                    'created_by' => auth()->id() ?? 1,
                ]);
            }

            // Find the last auto-generated reference number
            $lastItem = PmProductItem::whereNotNull('ref_number_auto')->orderBy('id', 'desc')->first();
            $lastRefNumber = $lastItem ? intval(substr($lastItem->ref_number_auto, 3)) : 0;

            // Create the new product items and associate them with the product (either new or existing).
            foreach ($request->items as $item) {
                $nextRefNumber = $lastRefNumber + 1;
                $autoRefNumber = 'GB_'.str_pad($nextRefNumber, 5, '0', STR_PAD_LEFT);
                $lastRefNumber++; // Increment for the next item in this loop

                $productItem = PmProductItem::create([
                    'pm_product_id' => $product->id,
                    'pm_brands_id' => $request->brand ?? null, // Optional
                    'pm_variation_id' => $item['variation_id'] ?? null,
                    'pm_variation_value_id' => $item['variation_value_id'] ?? null,
                    'product_name' => $item['name'],
                    // 'bin_code' => generate or nullable
                    'ref_number_auto' => $autoRefNumber,
                    'reference_number' => $item['reference_number'] ?? null,
                    'selling_price' => $item['selling_price'] ?? 0,
                    'status' => 1,
                    'created_by' => auth()->id() ?? 1,
                    'updated_by' => auth()->id() ?? 1,
                ]);

                // Attach product types if they are provided
                if (isset($item['product_types']) && is_array($item['product_types'])) {
                    $productItem->productTypes()->attach($item['product_types']);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Product saved successfully!']);
            }

            return redirect()->back()->with('success', 'Product saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error saving product: '.$e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Error saving product: '.$e->getMessage());
        }
    }

    public function productRegistrationIndex()
    {
        $productTypes = PmProductType::where('status', 1)->get();
        $brands = PmBrand::where('status', 1)->get();
        $variants = PmVariation::with('values')->where('status', 1)->get();
        $unitOfMeasurement = CommonVariables::$UnitOfMeasurement;

        return view('productManagement.productRegistration', compact('productTypes', 'brands', 'variants', 'unitOfMeasurement'));
    }

    public function configurationIndex()
    {
        $unitOfMeasurement = CommonVariables::$UnitOfMeasurement;

        return view('productManagement.configuration', compact('unitOfMeasurement'));
    }

    // Product Types
    public function fetchProductTypes()
    {
        $types = PmProductType::where('status', 1)->get();

        return response()->json($types);
    }

    public function storeProductType(Request $request)
    {
        $request->validate(['product_type_name' => 'required|string|max:100']);
        PmProductType::create([
            'product_type_name' => $request->product_type_name,
            'description' => $request->description,
            'status' => 1,
            'created_by' => auth()->id() ?? 1, // Fallback for dev if needed
            'updated_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateProductType(Request $request)
    {
        $request->validate(['id' => 'required', 'product_type_name' => 'required|string|max:100']);
        PmProductType::where('id', $request->id)->update([
            'product_type_name' => $request->product_type_name,
            'description' => $request->description,
            'updated_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteProductType(Request $request)
    {
        PmProductType::where('id', $request->id)->delete();

        return response()->json(['success' => true]);
    }

    // Brands
    public function fetchBrands()
    {
        $brands = \App\Models\PmBrand::where('status', 1)->get();

        return response()->json($brands);
    }

    public function storeBrand(Request $request)
    {
        $request->validate([
            'brand_name' => 'required|string|max:150',
            'brand_code' => 'nullable|string|max:45',
        ]);
        \App\Models\PmBrand::create([
            'brand_name' => $request->brand_name,
            'brand_code' => $request->brand_code,
            'status' => 1,
            'created_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateBrand(Request $request)
    {
        $request->validate(['id' => 'required', 'brand_name' => 'required|string|max:150']);
        \App\Models\PmBrand::where('id', $request->id)->update([
            'brand_name' => $request->brand_name,
            'brand_code' => $request->brand_code,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteBrand(Request $request)
    {
        \App\Models\PmBrand::where('id', $request->id)->delete();

        return response()->json(['success' => true]);
    }

    // Variations
    public function fetchVariations()
    {
        $variations = \App\Models\PmVariation::with('values')->where('status', 1)->get();

        return response()->json($variations);
    }

    public function storeVariation(Request $request)
    {
        $request->validate(['variation_name' => 'required|string|max:150']);
        \App\Models\PmVariation::create([
            'variation_name' => $request->variation_name,
            'status' => 1,
            'created_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateVariation(Request $request)
    {
        $request->validate(['id' => 'required', 'variation_name' => 'required|string|max:150']);
        \App\Models\PmVariation::where('id', $request->id)->update([
            'variation_name' => $request->variation_name,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteVariation(Request $request)
    {
        \App\Models\PmVariation::where('id', $request->id)->delete();

        return response()->json(['success' => true]);
    }

    // Variation Values
    public function fetchVariationValues(Request $request)
    {
        $unitOfMeasurement = CommonVariables::$UnitOfMeasurement;
        $values = \App\Models\PmVariationValue::where('pm_variation_id', $request->variation_id)->where('status', 1)->get();

        return response()->json([
            'values' => $values,
            'unitOfMeasurement' => $unitOfMeasurement,
        ]);
    }

    public function storeVariationValue(Request $request)
    {
        $request->validate([
            'variation_id' => 'required',
            'variation_value' => 'required|string|max:45',
            'unit_of_measurement_id' => 'required',
        ]);
        \App\Models\PmVariationValue::create([
            'pm_variation_id' => $request->variation_id,
            'variation_value' => $request->variation_value,
            'unit_of_measurement_id' => $request->unit_of_measurement_id,
            'status' => 1,
            'created_by' => auth()->id() ?? 1,
            'updated_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateVariationValue(Request $request)
    {
        $request->validate(['id' => 'required', 'variation_value' => 'required|string|max:45', 'unit_of_measurement_id' => 'required']);
        \App\Models\PmVariationValue::where('id', $request->id)->update([
            'variation_value' => $request->variation_value,
            'unit_of_measurement_id' => $request->unit_of_measurement_id,
            'updated_by' => auth()->id() ?? 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function deleteVariationValue(Request $request)
    {
        \App\Models\PmVariationValue::where('id', $request->id)->delete();

        return response()->json(['success' => true]);
    }

    // --- Product Search & Details Enhancements ---

    public function searchProducts(Request $request)
    {
        $query = $request->get('query');

        $products = PmProduct::where('product_name', 'like', "%{$query}%")
            ->where('status', 1)
            ->select('id', 'product_name', 'product_description')
            ->limit(10)
            ->get();

        return response()->json($products);
    }

    public function fetchProductItems(Request $request)
    {
        $productId = $request->get('product_id');
        $brandId = $request->get('brand_id'); // Required to trigger query

        // If brand_id is missing or empty → return empty array
        if (empty($brandId)) {
            return response()->json([]);
        }

        $items = PmProductItem::with(['variation', 'variationValue'])
            ->where('pm_product_id', $productId)
            ->where('pm_brands_id', $brandId)
            ->where('status', 1)
            ->get();

        return response()->json($items);
    }

    public function productManageIndex()
    {
        // Fetch all product types first
        $productTypes = PmProductType::where('status', 1)->get();
        $brands = PmBrand::where('status', 1)->get();

        // Fetch all product items with necessary relationships
        $items = PmProductItem::with(['product', 'brand', 'variationValue', 'variation', 'productTypes'])
            ->where('status', 1)
            ->get();

        // Calculate Stats based on Product Type
        $stats = [
            'total' => $items->count(),
            'active' => $items->where('status', 1)->count(),
        ];

        // Dynamic stats by type ID - count items that have this type (items can have multiple types)
        foreach ($productTypes as $type) {
            $stats[$type->id] = $items->filter(function ($item) use ($type) {
                return $item->productTypes->contains($type->id);
            })->count();
        }

        // Transform for View
        $formattedProducts = $items->map(function ($item) {
            // Get all product types for this item
            $allTypes = $item->productTypes;

            // For display purposes, use the first type if any exist
            $firstType = $allTypes->first();
            $typeId = $firstType ? $firstType->id : 'default';
            $typeName = $firstType ? $firstType->product_type_name : 'General';

            // Prepare all type IDs and names for the frontend
            $allTypeIds = $allTypes->pluck('id')->toArray();
            $allTypeNames = $allTypes->pluck('product_type_name')->toArray();

            $unitId = $item->variationValue->unit_of_measurement_id ?? null;
            $unit = $unitId ? (CommonVariables::$UnitOfMeasurement[$unitId] ?? '') : '';

            $variationValue = $item->variationValue->variation_value ?? '';
            $unitDisplay = $variationValue ? $variationValue.' '.$unit : '-';

            return [
                'id' => $item->id,
                'name' => $item->product_name, // Using Item name as specific product name
                'category' => $typeName,
                'brand' => $item->brand->brand_name ?? 'No Brand',
                'autoRef' => $item->ref_number_auto,
                'manualRef' => $item->reference_number,
                'unit' => $unitDisplay,
                'type' => $typeId, // This is now the ID
                'typeName' => $typeName, // Pass name for UI label if needed
                'allTypeIds' => $allTypeIds, // Include all type IDs
                'allTypeNames' => $allTypeNames, // Include all type names
                'isActive' => $item->status == 1,
            ];
        });

        return view('productManagement.productManage', [
            'products' => $formattedProducts,
            'stats' => $stats,
            'productTypes' => $productTypes,
            'brands' => $brands,
        ]);
    }

    public function updateProductStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pm_product_item,id', // Validate against the item table
        ]);

        try {
            DB::beginTransaction();

            // Find the product item
            $productItem = PmProductItem::findOrFail($request->id);

            // Update status to 0 (Inactive)
            // You might want to toggle status instead: $status = $productItem->status == 1 ? 0 : 1;
            // But requirement says "Archive", which implies setting to inactive/archived.
            $productItem->update([
                'status' => 0,
                'updated_by' => auth()->id() ?? 1,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product archived successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error archiving product: '.$e->getMessage()], 500);
        }
    }

    public function updateProductItemTypes(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:pm_product_item,id',
            'product_types' => 'array', // Can be empty if clearing all types
            'product_types.*' => 'exists:pm_product_type,id',
        ]);

        try {
            DB::beginTransaction();

            $productItem = PmProductItem::findOrFail($request->item_id);

            // Sync the product types
            // If product_types is not present or empty, sync([]) will remove all associations
            $types = $request->input('product_types', []);
            $productItem->productTypes()->sync($types);

            // Touch updated_by
            $productItem->update(['updated_by' => auth()->id() ?? 1]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product types updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'Error updating product types: '.$e->getMessage()], 500);
        }
    }
}
