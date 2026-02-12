<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierManagementController extends Controller
{
    public function supplierManageIndex()
    {
        // Fetch suppliers with their related data
        $suppliers = Supplier::with(['contacts', 'supplierProductItems.productItem'])->withCount('purchaseOrders')->get()->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'status' => $supplier->status,
                'rating' => $supplier->rating,
                'totalOrders' => $supplier->purchase_orders_count,
                'onTimeDelivery' => $supplier->on_time_delivery,
                'qualityScore' => $supplier->quality_score,
                'leadTime' => $supplier->lead_time,
                'creditLimit' => $supplier->credit_limit,
                'currentCredit' => $supplier->current_credit,
                'paymentTerms' => $supplier->payment_terms,
                'tags' => $supplier->tags,
                'categories' => $supplier->categories,
                'registrationNumber' => $supplier->registration_number,
                'taxId' => $supplier->tax_id,
                'address' => $supplier->address,
                'website' => $supplier->website,
                'bankDetails' => $supplier->bank_details,
                'contacts' => $supplier->contacts ? $supplier->contacts->map(function ($contact) {
                    return [
                        'name' => $contact->name,
                        'position' => $contact->position,
                        'email' => $contact->email,
                        'phone' => $contact->phone,
                        'isPrimary' => $contact->is_primary,
                    ];
                })->toArray() : [],
                'products' => $supplier->supplierProductItems && $supplier->supplierProductItems->count() > 0 ? $supplier->supplierProductItems->map(function ($supplierProductItem) {
                    return [
                        'product_item_id' => $supplierProductItem->product_item_id,
                        'productName' => $supplierProductItem->productItem ? $supplierProductItem->productItem->product_name : 'N/A',
                        'sku' => $supplierProductItem->sku ?? ($supplierProductItem->productItem ? $supplierProductItem->productItem->product_name : 'N/A'),
                        'category' => $supplierProductItem->category,
                        'unit' => $supplierProductItem->unit,
                        'unitPrice' => $supplierProductItem->unit_price,
                    ];
                })->toArray() : [],
                'contracts' => $supplier->contracts,
                'documents' => $supplier->documents,
            ];
        })->toArray();

        return view('supplierManagement.supplierManage', compact('suppliers'));
    }

    public function createSupplier(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'registrationNumber' => 'nullable|string|max:100',
            'taxId' => 'nullable|string|max:100',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'bankDetails' => 'nullable|string', // JSON string from frontend
            'categories' => 'nullable|string', // JSON string from frontend
            'paymentTerms' => 'required|string|in:cash,credit-7,credit-15,credit-30,credit-60',
            'creditLimit' => 'required|numeric|min:0',
            'minimumOrder' => 'nullable|numeric|min:0',
            'leadTime' => 'nullable|integer|min:0',
            'tags' => 'nullable|string', // JSON string from frontend
            'notes' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.position' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'contacts.*.mobile' => 'nullable|string|max:20',
            'contacts.*.is_primary' => 'nullable|in:true,false,1,0,0.0,1.1',
            'products' => 'nullable|array',
            'products.*.name' => 'required_with:products|string|max:255',
            'products.*.price' => 'required_with:products|numeric|min:0',
            'products.*.minimumOrder' => 'nullable|numeric|min:0',
            'products.*.sku' => 'required_with:products|string|max:255',
            'products.*.product_item_id' => 'required_with:products|integer|exists:pm_product_item,id',
        ]);

        // Create the supplier
        $supplier = Supplier::create([
            'name' => $validatedData['name'],
            'registration_number' => $validatedData['registrationNumber'] ?? null,
            'tax_id' => $validatedData['taxId'] ?? null,
            'address' => $validatedData['address'],
            'website' => $validatedData['website'] ?? null,
            'status' => 'active', // Default to active
            'rating' => 0, // Default rating
            'total_orders' => 0, // Default value
            'on_time_delivery' => 0, // Default value
            'quality_score' => 0, // Default value
            'lead_time' => $validatedData['leadTime'] ?? null,
            'credit_limit' => $validatedData['creditLimit'],
            'current_credit' => 0, // Default value
            'payment_terms' => $validatedData['paymentTerms'],
            'tags' => $validatedData['tags'] ? json_decode($validatedData['tags'], true) : null,
            'categories' => $validatedData['categories'] ? json_decode($validatedData['categories'], true) : null,
            'bank_details' => isset($validatedData['bankDetails']) ? json_decode($validatedData['bankDetails'], true) : null,
            'documents' => [], // Default empty array
            'contracts' => [], // Default empty array
        ]);

        // Process contacts if provided
        if ($request->has('contacts')) {
            $hasPrimary = false;

            foreach ($request->contacts as $contactData) {
                // Ensure is_primary is properly converted to boolean
                $isPrimary = false;
                if (isset($contactData['is_primary'])) {
                    $rawValue = $contactData['is_primary'];
                    if (is_bool($rawValue)) {
                        $isPrimary = $rawValue;
                    } elseif (is_string($rawValue)) {
                        $isPrimary = strtolower($rawValue) === 'true' || $rawValue === '1';
                    } elseif (is_numeric($rawValue)) {
                        $isPrimary = (bool) $rawValue;
                    } else {
                        $isPrimary = (bool) $rawValue;
                    }
                }

                // If no primary contact has been set yet, set the first one as primary
                if (!$hasPrimary && $isPrimary) {
                    $hasPrimary = true;
                }

                $supplier->contacts()->create([
                    'name' => $contactData['name'] ?? '',
                    'position' => $contactData['position'] ?? '',
                    'email' => $contactData['email'] ?? '',
                    'phone' => $contactData['phone'] ?? '',
                    'mobile' => $contactData['mobile'] ?? '',
                    'is_primary' => $isPrimary,
                ]);
            }

            // If no contact was marked as primary, set the first one as primary
            if (!$hasPrimary && count($request->contacts) > 0) {
                $firstContact = $supplier->contacts()->first();
                if ($firstContact) {
                    $firstContact->update(['is_primary' => true]);
                }
            }
        }

        // Process products if provided
        if ($request->has('products')) {
            foreach ($request->products as $productData) {
                // Use the product_item_id directly since it was selected from the search
                $productItemId = (int) $productData['product_item_id'];
                $productItem = \App\Models\PmProductItem::find($productItemId);

                if ($productItem) {
                    // Link the supplier with the product item
                    $supplier->supplierProductItems()->create([
                        'product_item_id' => $productItemId,
                        'unit_price' => $productData['price'] ?? 0,
                        'minimum_order' => $productData['minimumOrder'] ?? 0,
                        'sku' => $productData['sku'], // Use the manually entered SKU
                        'category' => null, // Can be updated later
                        'unit' => null, // Can be updated later
                    ]);
                } else {
                    // Return error if product item doesn't exist
                    return response()->json([
                        'success' => false,
                        'message' => 'One of the selected products (ID: ' . $productItemId . ') does not exist in the system. Please refresh and try again.',
                    ], 422);
                }
            }
        }



        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'supplier' => $supplier
        ], 201);
    }

    public function updateSupplier(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'registrationNumber' => 'nullable|string|max:100',
            'taxId' => 'nullable|string|max:100',
            'address' => 'required|string',
            'website' => 'nullable|url|max:255',
            'bankDetails' => 'nullable|string', // JSON string from frontend
            'categories' => 'nullable|string', // JSON string from frontend
            'paymentTerms' => 'required|string|in:cash,credit-7,credit-15,credit-30,credit-60',
            'creditLimit' => 'required|numeric|min:0',
            'minimumOrder' => 'nullable|numeric|min:0',
            'leadTime' => 'nullable|integer|min:0',
            'tags' => 'nullable|string', // JSON string from frontend
            'notes' => 'nullable|string',
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.position' => 'nullable|string|max:255',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'contacts.*.mobile' => 'nullable|string|max:20',
            'contacts.*.is_primary' => 'nullable|in:true,false,1,0,0.0,1.1',
            'products' => 'nullable|array',
            'products.*.name' => 'required_with:products|string|max:255',
            'products.*.price' => 'required_with:products|numeric|min:0',
            'products.*.minimumOrder' => 'nullable|numeric|min:0',
            'products.*.sku' => 'required_with:products|string|max:255',
            'products.*.product_item_id' => 'required_with:products|integer|exists:pm_product_item,id',
        ]);

        $supplier = Supplier::findOrFail($validatedData['id']);

        $supplier->update([
            'name' => $validatedData['name'],
            'registration_number' => $validatedData['registrationNumber'] ?? null,
            'tax_id' => $validatedData['taxId'] ?? null,
            'address' => $validatedData['address'],
            'website' => $validatedData['website'] ?? null,
            'lead_time' => $validatedData['leadTime'] ?? null,
            'credit_limit' => $validatedData['creditLimit'],
            'payment_terms' => $validatedData['paymentTerms'],
            'tags' => $validatedData['tags'] ? json_decode($validatedData['tags'], true) : null,
            'categories' => $validatedData['categories'] ? json_decode($validatedData['categories'], true) : null,
            'bank_details' => isset($validatedData['bankDetails']) ? json_decode($validatedData['bankDetails'], true) : null,
        ]);

        // Process contacts
        $supplier->contacts()->delete(); // Easier to replace all for now
        if ($request->has('contacts')) {
            $hasPrimary = false;

            foreach ($request->contacts as $contactData) {
                // Ensure is_primary is properly converted to boolean
                $isPrimary = false;
                if (isset($contactData['is_primary'])) {
                    $rawValue = $contactData['is_primary'];
                    if (is_bool($rawValue)) {
                        $isPrimary = $rawValue;
                    } elseif (is_string($rawValue)) {
                        $isPrimary = strtolower($rawValue) === 'true' || $rawValue === '1';
                    } elseif (is_numeric($rawValue)) {
                        $isPrimary = (bool) $rawValue;
                    } else {
                        $isPrimary = (bool) $rawValue;
                    }
                }

                // If no primary contact has been set yet, set the first one as primary
                if (!$hasPrimary && $isPrimary) {
                    $hasPrimary = true;
                }

                $supplier->contacts()->create([
                    'name' => $contactData['name'] ?? '',
                    'position' => $contactData['position'] ?? '',
                    'email' => $contactData['email'] ?? '',
                    'phone' => $contactData['phone'] ?? '',
                    'mobile' => $contactData['mobile'] ?? '',
                    'is_primary' => $isPrimary,
                ]);
            }

            // If no contact was marked as primary, set the first one as primary
            if (!$hasPrimary && count($request->contacts) > 0) {
                $firstContact = $supplier->contacts()->first();
                if ($firstContact) {
                    $firstContact->update(['is_primary' => true]);
                }
            }
        }

        // Process products
        $supplier->supplierProductItems()->delete(); // Easier to replace all for now
        if ($request->has('products')) {
            foreach ($request->products as $productData) {
                $productItemId = (int) $productData['product_item_id'];
                $productItem = \App\Models\PmProductItem::find($productItemId);

                if ($productItem) {
                    $supplier->supplierProductItems()->create([
                        'product_item_id' => $productItemId,
                        'unit_price' => $productData['price'] ?? 0,
                        'minimum_order' => $productData['minimumOrder'] ?? 0,
                        'sku' => $productData['sku'],
                        'category' => null,
                        'unit' => null,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier
        ], 200);
    }

    public function searchProductItems(Request $request)
    {
        $query = $request->input('query');

        // Search for product items that match the query
        $products = \App\Models\PmProductItem::with(['product', 'variationValue', 'productTypes'])
            ->where('product_name', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                // Determine Category (use first product type or 'General')
                $category = 'General';
                if ($item->productTypes && $item->productTypes->count() > 0) {
                    $category = $item->productTypes->first()->product_type_name;
                }

                // Determine Unit
                $unit = 'unit';
                if ($item->variationValue) {
                    $unit = $item->variationValue->variation_value;
                }

                // Determine SKU (Order of preference: Auto Ref -> Manual Ref -> Bin Code)
                $sku = $item->ref_number_auto ?? ($item->reference_number ?? ($item->bin_code ?? 'N/A'));

                return [
                    'id' => $item->id,
                    'product_name' => $item->product_name ?? ($item->product->product_name ?? 'Unknown Product'),
                    'category' => $category,
                    'unit' => $unit,
                    'sku' => $sku
                ];
            });

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }


}
