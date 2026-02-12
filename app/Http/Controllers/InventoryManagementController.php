<?php

namespace App\Http\Controllers;

use App\CommonVariables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryManagementController extends Controller
{
    public function inventoryManageIndex()
    {
        return view('inventoryManagement.overview');
    }

    public function inventoryDashboardIndex()
    {
        // 1. Calculate Overall Stats
        // Total Inventory Value
        $totalInventoryValue = \App\Models\StmStock::select(DB::raw('SUM(quantity * costing_price) as total_value'))
            ->value('total_value') ?? 0;

        // Low Stock Items (< Reorder Point)
        // Since reorder point is on PmProductItem (or defaulted), we might need to join or load.
        // For efficiency, let's do a basic check where quantity < 50 (temporary placeholder logic mostly used)
        // Or better, fetch items and check.
        $itemStats = \App\Models\PmProductItem::with('stocks')->get();

        $lowStockItems = 0;
        $expiringItems = 0;

        foreach ($itemStats as $item) {
            $currentStock = $item->stocks->sum('quantity');
            $reorderPoint = $item->reorder_point ?? 50; // Default

            if ($currentStock < $reorderPoint && $currentStock > 0) {
                $lowStockItems++;
            }

            // Check expiring in next 7 days
            $hasExpiring = $item->stocks->contains(function ($stock) {
                return $stock->expiry_date
                    && \Carbon\Carbon::parse($stock->expiry_date)->isFuture()
                    && \Carbon\Carbon::parse($stock->expiry_date)->diffInDays(now()) <= 7;
            });
            if ($hasExpiring) {
                $expiringItems++;
            }
        }

        // Pending Transfers (assuming table is stm_stock_transfer or similar, defaulting to 0 if not exists yet)
        // If table doesn't exist yet based on known models, use placeholder 0.
        $pendingTransfers = 0;
        // Example if exists: \App\Models\StmStockTransfer::where('status', 'pending')->count();

        // 2. Recent Activity (Merge GRNs, maybe transfers later)
        // Fetch last 5 GRNs
        $recentGrns = \App\Models\StmGrn::with('supplier')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($grn) {
                return [
                    'id' => 'GRN-' . $grn->id,
                    'type' => 'grn',
                    'description' => "GRN {$grn->grn_number} from " . ($grn->supplier->name ?? 'Unknown'),
                    'timestamp' => $grn->created_at,
                    'user' => 'Admin', // Replace with relationship if available e.g., $grn->creator->name
                    'value' => $grn->invoice_amount
                ];
            });

        // Merge and sort if we had multiple sources.
        $recentActivity = $recentGrns;

        // 3. Warehouse Status (Mock Data for now as we don't have sensors linked)
        $warehouseStatuses = [
            [
                'name' => 'Main Warehouse',
                'value' => $totalInventoryValue * 0.6, // Estimated share
                'items' => $itemStats->count(),
                'status' => 'normal',
                'temperature' => 22,
                'humidity' => 45
            ],
            [
                'name' => 'Freezer 1 (-18°C)',
                'value' => $totalInventoryValue * 0.2,
                'items' => 150, // Mock
                'status' => 'normal',
                'temperature' => -18,
                'humidity' => 65
            ]
        ];

        $stats = [
            'totalInventoryValue' => $totalInventoryValue,
            'lowStockItems' => $lowStockItems,
            'expiringItems' => $expiringItems,
            'pendingTransfers' => $pendingTransfers,
            'recentActivity' => $recentActivity
        ];

        return view('inventoryManagement.inventoryDashboard', compact('stats', 'warehouseStatuses'));
    }
    public function kitchenInventoryIndex()
    {
        return view('inventoryManagement.kitchenInventory');
    }


    public function supplierCompareIndex()
    {
        return view('inventoryManagement.supplierCompare');
    }
    public function purchaseOrderManageIndex(Request $request)
    {
        // 1. Fetch Orders from Database
        $query = \App\Models\StmPurchaseOrder::with([
            'supplier.primaryContact', // Eager load primary contact
            'items.productItem.product',
            'items.productItem.brand',
            'items.productItem.variation',
            'items.productItem.variationValue',
            'auditTrails.user'
        ])->orderBy('created_at', 'desc');

        // ... (filters logic remains same)

        $allOrders = $query->get();

        // 2. Map to View Structure
        $orders = $allOrders->map(function ($order) {
            $primaryContact = $order->supplier->primaryContact ?? null;

            return (object) [
                'id' => $order->id,
                'po_number' => $order->po_number, // Use the actual column
                'supplier_id' => $order->supplier_id,
                'supplier_name' => $order->supplier->name ?? 'Unknown Supplier',
                'supplier_email' => $primaryContact ? $primaryContact->email : 'N/A',
                'supplier_phone' => $primaryContact ? $primaryContact->phone : 'N/A',
                'contact_person' => $primaryContact ? $primaryContact->name : 'N/A',
                'items' => $order->items->map(function ($item) {
                    // ... (items mapping logic remains same)
                    // Name Builder
                    $name = $item->productItem->product_name
                        ?? ($item->productItem->product->product_name ?? 'Unknown Item');

                    return [
                        'product_id' => $item->product_item_id,
                        'product_name' => $name,
                        'category' => 'General', // TODO: Fetch from Product Master
                        'quantity' => $item->quantity,
                        'unit' => 'unit', // TODO: Fetch unit
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->quantity * $item->unit_price,
                        'received_quantity' => $item->grn_received_quantity ?? 0
                    ];
                }),
                // Map status 0 to 'pending', else use existing
                'status' => $order->status === CommonVariables::$pending ? CommonVariables::$pending : ($order->status ?? CommonVariables::$pending),
                'grand_total' => $order->items->sum(fn($i) => $i->quantity * $i->unit_price), // Recalculate or use stored total
                'created_by' => 'Admin', // Placeholder or relation
                'created_at' => $order->created_at,
                'expected_delivery_date' => $order->delivery_date,
                'payment_terms' => $order->payment_terms,
                'notes' => $order->notes,
                'audit_trail' => $order->auditTrails->map(function ($log) {
                    return [
                        'action' => $log->action,
                        'created_at' => $log->created_at,
                        'user_name' => $log->user ? $log->user->name : 'System',
                        'role' => $log->user_role ?? 'User',
                        'details' => $log->description,
                        'previous_status' => $log->previous_status,
                        'new_status' => $log->new_status
                    ];
                }),
                'approve_url' => route('purchaseOrder.approve', $order->id),
                'send_url' => route('purchaseOrder.send', $order->id),
                'grn_url' => url('/create-grn') . '?po_id=' . $order->id,
                'download_pdf_url' => route('purchaseOrder.downloadPdf', $order->id)
            ];
        });

        // Filter by status for the main list
        if ($request->has('status') && $request->status !== 'all') {
            $status = $request->status;

            // Map string request to integer status
            $statusMap = [
                'pending' => CommonVariables::$pending,
                'approved' => CommonVariables::$approved,
                'sent' => CommonVariables::$sent,
                'partially-received' => CommonVariables::$partiallyReceived,
                'received' => CommonVariables::$received,
                'closed' => CommonVariables::$closed,
                'cancelled' => CommonVariables::$cancelled,
            ];

            $orders = $orders->filter(function ($order) use ($status, $statusMap) {
                if ($status === 'history')
                    return in_array($order->status, [CommonVariables::$received, CommonVariables::$closed, CommonVariables::$cancelled]);
                if ($status === 'receiving')
                    return in_array($order->status, [CommonVariables::$sent, CommonVariables::$partiallyReceived]);

                // If direct match in map
                if (isset($statusMap[$status])) {
                    return $order->status === $statusMap[$status];
                }

                return false;
            })->values();
        }

        // 3. specific stats
        $stats = [
            'total' => $allOrders->count(),
            'totalValue' => $allOrders->sum('grand_total'),
            'draft' => $allOrders->where('status', 'draft')->count(),
            'pending' => $allOrders->where(fn($o) => $o->status === CommonVariables::$pending)->count(),
            'pendingValue' => $allOrders->where(fn($o) => $o->status === CommonVariables::$pending)->sum('grand_total'),
            'approved' => $allOrders->where('status', CommonVariables::$approved)->count(),
            'sent' => $allOrders->where('status', CommonVariables::$sent)->count(),
            'partiallyReceived' => $allOrders->where('status', CommonVariables::$partiallyReceived)->count(),
            'received' => $allOrders->where('status', CommonVariables::$received)->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'orders' => $orders,
                'stats' => $stats
            ]);
        }

        return view('inventoryManagement.purchaseOrderManage', compact('orders', 'stats'));
    }

    public function createPurchaseOrderIndex()
    {
        // 1. Fetch Suppliers
        $suppliers = \App\Models\Supplier::with('primaryContact')->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'contactPerson' => $s->primaryContact ? $s->primaryContact->name : 'N/A',
                'email' => $s->primaryContact ? $s->primaryContact->email : 'N/A',
                'phone' => $s->primaryContact ? $s->primaryContact->phone : 'N/A',
                'rating' => $s->rating ?? 0,
                'onTimeDelivery' => $s->on_time_delivery ?? 0,
                'leadTime' => $s->lead_time ?? 0,
                'paymentTerms' => $s->payment_terms ?? 'credit-30',
                'minimumOrder' => 0,
                'totalOrders' => $s->total_orders ?? 0
            ];
        });

        // 2. Fetch All Active Products from Product Master
        $products = \App\Models\PmProductItem::with(['product', 'brand', 'variation', 'variationValue', 'supplierProductItems'])
            ->where('status', 1) // Assuming 'active' is the status for usable items
            ->get()
            ->map(function ($item) {
                // Prepare supplier specific data map
                $supplierData = [];
                foreach ($item->supplierProductItems as $spi) {
                    $supplierData[$spi->supplier_id] = [
                        'sku' => $spi->sku,
                        'price' => $spi->unit_price,
                    ];
                }

                return [
                    'id' => $item->id,
                    'supplierId' => null, // Generic item, handles multiple suppliers
                    'supplierData' => $supplierData, // Map: supplierId => { sku, price }
                    'name' => $item->product_name ?? ($item->product->product_name . ' - ' . ($item->brand->brand_name ?? '')),
                    'category' => 'General', // PmProductItem doesn't seem to have category directly, using placeholder
                    'supplierSKU' => 'N/A', // Will be dynamic based on selected supplier
                    'supplierPrice' => 0,   // Will be dynamic
                    'unit' => 'unit',       // Default or fetch if available
                    'type' => 'ingredient',  // Default
                    'ref_number' => $item->ref_number_auto ?? $item->reference_number
                ];
            });

        return view('inventoryManagement.createPurchaseOrder', compact('suppliers', 'products'));
    }

    public function storePurchaseOrder(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'payment_terms' => 'required',
            'delivery_date' => 'required|date',
            'products_json' => 'required',
        ]);

        try {
            DB::beginTransaction();


            // Generate PO Number
            $lastPo = \App\Models\StmPurchaseOrder::latest('id')->first();
            $nextNumber = 1;

            if ($lastPo) {
                if ($lastPo->po_number) {
                    // Extract number from PO-XXXXX
                    $nextNumber = intval(substr($lastPo->po_number, 3)) + 1;
                } else {
                    // Fallback for old orders: use last ID + 1
                    $nextNumber = $lastPo->id + 1;
                }
            }

            $poNumber = 'PO-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);


            // Create PO
            $po = \App\Models\StmPurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'po_number' => $poNumber,
                'payment_terms' => $request->payment_terms,
                'delivery_date' => $request->delivery_date,
                'notes' => $request->notes,
                'created_by' => Auth::id(), // Ensure Auth is imported
                'updated_by' => Auth::id(),
                'status' => CommonVariables::$pending,
            ]);

            // Create PO Items
            $cartItems = json_decode($request->products_json, true);

            if (!is_array($cartItems)) {
                throw new \Exception("Invalid products data");
            }

            foreach ($cartItems as $item) {
                \App\Models\StmPurchaseOrderHasProductItem::create([
                    'purchase_order_id' => $po->id,
                    'product_item_id' => $item['id'],
                    'unit_price' => $item['supplierPrice'],
                    'quantity' => $item['quantity'],
                    'is_completed' => 0,
                    'grn_received_quantity' => 0,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            // Log Audit
            $this->logAudit($po->id, 'PO Created', 'Purchase order created', null, 'pending');

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Purchase Order created successfully!', 'redirect' => route('purchaseOrderManage.index')]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to create Purchase Order: ' . $e->getMessage()], 500);
        }
    }

    public function approvePurchaseOrder($id)
    {
        try {
            DB::beginTransaction();

            $order = \App\Models\StmPurchaseOrder::findOrFail($id);
            $order->status = CommonVariables::$approved;
            $order->updated_by = Auth::id();
            $order->save();

            $this->logAudit($order->id, 'PO Approved', 'Purchase order approved', 'pending', 'approved');

            DB::commit();

            return redirect()->route('purchaseOrderManage.index', ['success' => 'Purchase Order approved successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve Purchase Order: ' . $e->getMessage());
        }
    }

    public function sendToSupplier($id)
    {
        try {
            DB::beginTransaction();

            $order = \App\Models\StmPurchaseOrder::findOrFail($id);
            $order->status = CommonVariables::$sent; // 2
            $order->updated_by = Auth::id();
            $order->save();

            $this->logAudit($order->id, 'PO Sent to Supplier', 'Email sent to supplier', 'approved', 'sent');

            DB::commit();

            return redirect()->route('purchaseOrderManage.index', ['success' => 'Purchase Order sent to supplier successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to send Purchase Order: ' . $e->getMessage());
        }
    }

    public function downloadPdf($id)
    {
        $order = \App\Models\StmPurchaseOrder::with([
            'supplier.primaryContact',
            'items.productItem.product',
            'items.productItem.brand',
            'items.productItem.variation',
            'items.productItem.variationValue'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('inventoryManagement.purchaseOrderPdf', compact('order'));

        return $pdf->download('purchase_order_' . $order->po_number . '.pdf');
    }

    public function prepareCreateGRN(Request $request)
    {
        $request->validate(['po_id' => 'required']);
        session(['grn_po_id' => $request->po_id]);
        return response()->json(['success' => true]);
    }

    public function createGRNIndex(Request $request)
    {
        $purchaseOrder = null;
        $poId = $request->po_id ?? session('grn_po_id');

        // Optional: Forget the session so it doesn't persist on refresh if not intended,
        // but often better to keep it briefly or use flash. 
        // For now, let's keep it until overwritten or explicitly cleared.
        // Or better, if we want strict "once", we use pull (get and delete).
        // However, page refreshes might need it. Let's just use get.

        // If we want to support both URL and Session:
        if ($poId) {
            $po = \App\Models\StmPurchaseOrder::with([
                'supplier',
                'items.productItem.product',
                'items.productItem.brand',
                'items.productItem.variation',
                'items.productItem.variationValue'
            ])->find($poId);

            if ($po) {
                $purchaseOrder = [
                    'id' => $po->id,
                    'poNumber' => $po->po_number,
                    'supplierId' => $po->supplier_id,
                    'supplierName' => $po->supplier->name ?? 'Unknown Supplier',
                    'expectedDeliveryDate' => $po->delivery_date,
                    'grandTotal' => $po->grand_total ?? $po->items->sum(fn($i) => $i->quantity * $i->unit_price),
                    'items' => $po->items->map(function ($item) use ($po) {
                        $name = $item->productItem->product_name
                            ?? ($item->productItem->product->product_name ?? 'Unknown Item');

                        // Append Brand/Variation if available normally done in name builder
                        if (isset($item->productItem->brand->brand_name)) {
                            $name .= ' - ' . $item->productItem->brand->brand_name;
                        }

                        // Fetch detailed reception history
                        $history = \App\Models\StmGrn::where('purchase_order_id', $po->id)
                            ->join('stm_stock_in', 'stm_grn.id', '=', 'stm_stock_in.stm_grn_id')
                            ->where('stm_stock_in.pm_product_item_id', $item->product_item_id)
                            ->select(
                                'stm_grn.grn_number',
                                'stm_grn.created_at as grn_date',
                                'stm_stock_in.added_quantity',
                                'stm_stock_in.costing_price',
                                'stm_stock_in.updated_at' // Fallback for date
                            )
                            ->get()
                            ->map(function ($record) {
                            $date = $record->grn_date
                                ? \Carbon\Carbon::parse($record->grn_date)
                                : \Carbon\Carbon::parse($record->updated_at);

                            return [
                                'grn_number' => $record->grn_number,
                                'date' => $date->format('Y-m-d'),
                                'quantity' => $record->added_quantity,
                                'price' => $record->costing_price,
                                'total' => $record->added_quantity * $record->costing_price
                            ];
                        });

                        return [
                            'product_item_id' => $item->product_item_id,
                            'productName' => $name,
                            'category' => 'General', // Placeholder
                            'quantity' => $item->quantity,
                            'unit' => '', // Placeholder or fetch
                            'costing_price' => $item->unit_price, // Load from PO item
                            'selling_price' => 0, // Default to 0 or fetch if available in product master
                            'totalPrice' => $item->quantity * $item->unit_price,
                            'receivedQuantity' => $item->grn_received_quantity ?? 0, // Use the correct column name
                            'is_completed' => $item->is_completed, // Add completed status
                            'reception_history' => $history // Detailed history data
                        ];
                    })
                ];
            }
        }

        return view('inventoryManagement.createGRN', compact('purchaseOrder'));
    }

    public function cakeSectionIndex()
    {
        return view('inventoryManagement.cakeSection');
    }

    public function bakerySectionIndex()
    {
        return view('inventoryManagement.bakerySection');
    }

    public function manageStockTransfers()
    {
        // Fetch all requests
        $requests = \App\Models\StmStockOrderRequest::with([
            'requestingBranch',
            'supplyingBranch',
            'creator.userRole',
            'transfers.productItem.product',
            'transfers.productItem.productTypes',
            'transfers.productItem.brand',
            'transfers.stock', // Load Warehouse Stock
            'transfers.branchStock', // Load Branch Stock
            'history'
        ])->orderBy('created_at', 'desc')->get();

        $transfers = $requests->map(function ($req) {

            // Map Status
            $statusMap = [
                0 => 'pending',
                1 => 'approved', // Mapping InProgress to Approved for view compatibility
                2 => 'in-transit',
                3 => 'completed',
                4 => 'rejected'
            ];
            $status = $statusMap[$req->status] ?? 'pending';

            // Map Priority
            $priorityMap = [
                1 => 'low',
                2 => 'medium',
                3 => 'high',
                4 => 'urgent'
            ];
            $priority = $priorityMap[$req->priority_level] ?? 'medium';

            // From Section Logic
            $from = 'Unknown';
            if ($req->req_from_department_id) {
                $d = \App\Models\PlnDepartment::find($req->req_from_department_id);
                $branchName = $req->supplyingBranch->name ?? 'Unknown Branch';
                $from = ($d ? $d->name : 'Unknown Dept') . " ($branchName)";
            } elseif ($req->req_from_branch_id) {
                $from = $req->supplyingBranch->name ?? 'Warehouse';
                if ($req->req_from_branch_id == 1)
                    $from = 'Main Warehouse';
            }

            // To Section Logic
            $to = 'Unknown';
            if ($req->pln_department_id) {
                $d = \App\Models\PlnDepartment::find($req->pln_department_id);
                $branchName = $req->requestingBranch->name ?? 'Unknown Branch';
                $to = ($d ? $d->name : 'Unknown Dept') . " ($branchName)";
            } elseif ($req->um_branch_id) {
                $to = $req->requestingBranch->name ?? 'Unknown Branch';
            }

            // Items with Price and Expiry Logic
            $items = $req->transfers->map(function ($t) {
                $prodName = $t->productItem->product->product_name ?? 'Unknown Item';
                if (isset($t->productItem->brand->brand_name)) {
                    $prodName .= ' - ' . $t->productItem->brand->brand_name;
                }

                // Determine Source Stock to get Price/Expiry
                $stock = $t->stock ?? $t->branchStock;

                $price = 0;
                $expiry = 'N/A';

                if ($stock) {
                    // Try to get selling price, fallback to costing price
                    $price = $stock->selling_price ?? $stock->costing_price ?? 0;

                    // Format Expiry
                    if ($stock->expire_date) {
                        $expiry = \Carbon\Carbon::parse($stock->expire_date)->format('Y-m-d');
                    } elseif ($stock->expire_period) {
                        $expiry = $stock->expire_period . ' Days (Period)';
                    }
                }

                return [
                    'id' => $t->id,
                    'productName' => $prodName,
                    'category' => $t->productItem->productTypes->first()->name ?? 'General',
                    'quantity' => $t->requesting_quantity,
                    'approvedQuantity' => $t->approved_quantity, // Pass approved quantity
                    'unit' => 'unit',
                    'batchNumber' => $t->batch_number,
                    'status' => $t->approved_quantity !== null ? 'Approved' : 'Pending',
                    'unitPrice' => $price,
                    'expiry' => $expiry,
                    'qtyInUnit' => $t->qty_in_unit ?? 0, // Display logic: 0 if null
                    'unitType' => $t->productItem->variationValue->unit_of_measurement_id ?? null // To verify unit type on frontend if needed
                ];
            });

            // Calculate Total Value
            $totalValue = $items->sum(function ($item) {
                // Use approved quantity if available, otherwise requesting quantity
                $qty = $item['approvedQuantity'] !== null ? $item['approvedQuantity'] : $item['quantity'];
                return $qty * $item['unitPrice'];
            });

            // Audit Trail
            $audit = $req->history->map(function ($h) {
                $u = \App\Models\UmUser::find($h->created_by);
                // Fetch Role if possible. UmUser doesn't eagerly load role here unless we add it to history loop or query separately.
                // Optimally we'd eager load userRole in history query, but for now simple fetch:
                $role = 'User';
                if ($u && $u->userRole) {
                    $role = $u->userRole->user_role_name;
                } else if ($u) {
                    // Try lazy load if not eager loaded (N+1 risk but low volume)
                    $r = \App\Models\PmUserRole::find($u->user_role_id);
                    $role = $r ? $r->user_role_name : 'User';
                }

                return [
                    'action' => $h->action,
                    'timestamp' => $h->created_at->format('Y-m-d h:i A'),
                    'details' => $h->description,
                    'performedBy' => $u ? $u->user_name : 'System', // Use user_name
                    'role' => $role
                ];
            });

            // User & Role fetch
            $creatorName = $req->creator->user_name ?? 'Unknown';
            $creatorRole = $req->creator && $req->creator->userRole ? $req->creator->userRole->user_role_name : 'N/A';
            // Also need creator First/Last name for better display? user_name is likely username.
            if ($req->creator) {
                $creatorFullName = trim(($req->creator->first_name ?? '') . ' ' . ($req->creator->last_name ?? ''));
                if ($creatorFullName)
                    $creatorName = $creatorFullName;
            }

            // Fetch Approved By
            $approvedByName = 'N/A';
            $approvedAtDate = '';

            // Look for first approved item
            $approvedItem = $req->transfers->whereNotNull('approved_by')->first();
            if ($approvedItem) {
                $appUser = \App\Models\UmUser::find($approvedItem->approved_by);
                if ($appUser) {
                    $approvedByName = trim(($appUser->first_name ?? '') . ' ' . ($appUser->last_name ?? ''));
                    if (!$approvedByName)
                        $approvedByName = $appUser->user_name;
                }
                if ($approvedItem->approved_date) {
                    $approvedAtDate = \Carbon\Carbon::parse($approvedItem->approved_date)->format('Y-m-d h:i A');
                }
            }

            return [
                'id' => $req->id, // DB ID
                'requestNumber' => $req->order_number,
                'fromSection' => strtolower($from),
                'toSection' => strtolower($to),
                'requestedBy' => $creatorName,
                'requestedByRole' => $creatorRole,
                'requestedAt' => $req->created_at->format('Y-m-d h:i A'),
                'scheduledDate' => $req->scheduled_date ? \Carbon\Carbon::parse($req->scheduled_date)->format('Y-m-d') : 'N/A',
                'notes' => $req->notes ?? 'No additional notes provided.', // Pass notes
                'status' => $status,
                'priority' => $priority,
                'totalValue' => $totalValue,
                'approvedBy' => $approvedByName,
                'approvedAt' => $approvedAtDate,
                'items' => $items,
                'auditTrail' => $audit
            ];
        });

        // Calculate Stats
        $stats = [
            'pending' => $transfers->where('status', 'pending')->count(),
            'approved' => $transfers->where('status', 'approved')->count(),
            'inTransit' => $transfers->where('status', 'in-transit')->count(),
            'completed' => $transfers->where('status', 'completed')->count(),
            'rejected' => $transfers->where('status', 'rejected')->count(),
            'todayRequests' => $requests->where('created_at', '>=', now()->startOfDay())->count(),
            'todayCompleted' => $requests->where('status', 3)->where('updated_at', '>=', now()->startOfDay())->count(), // Approx
            'totalValue' => 0 // Placeholder
        ];

        return view('inventoryManagement.manageStockTransfers', compact('transfers', 'stats'));
    }

    public function createStockTransferIndex()
    {
        $user = Auth::user();

        // Fetch User's Branches
        $branches = $user->branches()->where('status', 1)->get();

        // Fetch Departments linked to these branches (or user specific, but assuming branched access gives dept access for now)
        // If user has specific department restrictions, we would check um_user_has_department. 
        // For now, collecting all departments of the user's branches.
        $branches->load('departments');

        $departments = $branches->pluck('departments')->flatten()->unique('id')->values();

        $productTypes = \App\Models\PmProductType::all();

        return view('inventoryManagement.createStockTransfer', compact('branches', 'departments', 'productTypes'));
    }

    public function getTransferableStock(Request $request)
    {
        $request->validate([
            'source_type' => 'required|in:warehouse,branch,department',
            'source_id' => 'required', // For warehouse, this might be ignored or checked against a constant
            'search' => 'nullable|string',
            'category' => 'nullable|string'
        ]);

        $items = collect();

        if ($request->source_type === 'warehouse') {
            // Fetch from StmStock (Main Warehouse)
            $query = \App\Models\StmStock::with(['stockIn.productItem.productTypes', 'stockIn.productItem.product', 'stockIn.productItem.brand'])
                ->where('quantity', '>', 0);

            // Filter by search/category if needed
            // (Simplified fetching for now, can optimize with joins if slow)
            $rawStock = $query->get();

            $items = $rawStock->map(function ($stock) {
                return $this->formatStockItem($stock, 'warehouse');
            });

        } elseif ($request->source_type === 'branch') {
            // Fetch from StmBranchStock
            $query = \App\Models\StmBranchStock::with(['productItem.productTypes', 'productItem.product', 'productItem.brand'])
                ->where('um_branch_id', $request->source_id)
                ->where('quantity', '>', 0);

            $rawStock = $query->get();

            $items = $rawStock->map(function ($stock) {
                return $this->formatStockItem($stock, 'branch');
            });
        } elseif ($request->source_type === 'department') {
            // Fetch from StmBranchStock associated with the department's branch
            // Assuming 1:1 or N:1 relation where dept belongs to branch.
            // We need to find the branch for this department first if not passed.
            // But usually, transfer from Dept implies local stock at Dept ?? 
            // The prompt says "branch unde show that related departments", implying Dept stock IS branch stock or partitioned.
            // User requirement: "load that transfer from branch or department related stock... use stm_stock and stm_branch_stock"
            // If Dept uses stm_branch_stock, we filter by branch. 
            // If there's no separate 'department_id' column in stm_branch_stock, it means Dept shares Branch stock.

            // Let's assume for now Dept maps to a Branch and shares that stock, OR we need to fetch the branch_id from the dept.
            $dept = \App\Models\PlnDepartment::find($request->source_id);
            if ($dept) {
                // Find branch(es) for this dept. A dept might be in multiple branches, but usually specific context.
                // Assuming the user selected a Dept which implies a specific location.
                // The current schema `um_branch_has_department` suggests M:N. 
                // However, stock must be located somewhere.
                // If the user selects a Dept, do we know which Branch?
                // Let's assume the UI passes the Branch ID if context is known, OR we pick the User's branch that has this dept.
                // For simplified logic matching the prompt "use stm_branch_stock", we look for stock in the branch this dept belongs to.
                // If stm_branch_stock DOES NOT have a department_id, then "Department Stock" == "Branch Stock".

                // Let's look up the branch(es) for this dept.
                $branchIds = DB::table('um_branch_has_department')->where('department_id', $request->source_id)->pluck('um_branch_id');

                if ($branchIds->isNotEmpty()) {
                    $query = \App\Models\StmBranchStock::with(['productItem.productTypes', 'productItem.product', 'productItem.brand'])
                        ->whereIn('um_branch_id', $branchIds)
                        ->where('quantity', '>', 0);

                    $rawStock = $query->get();
                    $items = $rawStock->map(function ($stock) {
                        return $this->formatStockItem($stock, 'branch');
                    });
                }
            }
        }

        // Apply filters (Search/Category) on the collection
        if ($request->search) {
            $search = strtolower($request->search);
            $items = $items->filter(function ($i) use ($search) {
                return str_contains(strtolower($i['name']), $search);
            });
        }
        if ($request->category && $request->category !== 'all') {
            $items = $items->where('category', $request->category);
        }

        return response()->json([
            'items' => $items->values(),
            // Unique categories for the filter dropdown
            'categories' => $items->pluck('category')->unique()->values()
        ]);
    }

    private function formatStockItem($stock, $type)
    {
        // Common formatter
        $productItem = ($type == 'warehouse') ? $stock->stockIn->productItem : $stock->productItem;
        $product = $productItem->product;

        $name = $product->product_name ?? 'Unknown';
        if (isset($productItem->brand->brand_name)) {
            $name .= ' - ' . $productItem->brand->brand_name;
        }

        return [
            'id' => $productItem->id, // Use Product Item ID for grouping/selection
            'stock_id' => $stock->id, // Specific stock record ID
            'name' => $name,
            'category' => $productItem->productTypes->first()?->name ?? 'General',
            'product_type_id' => $productItem->productTypes->first()?->id ?? null,
            'unit' => 'unit', // Placeholder
            'unitPrice' => $stock->costing_price ?? 0, // Or selling_price
            'stock' => $stock->quantity,
            'batch' => ($type == 'warehouse') ? $stock->batch_number : ($stock->stock->batch_number ?? 'N/A'),
            'expiry' => ($type == 'warehouse') ? $stock->expiry_date : ($stock->stock->expiry_date ?? 'N/A'),
        ];
    }

    public function storeGRN(Request $request)
    {
        $request->validate([
            'po_id' => 'required|exists:stm_purchase_order,id',
            'invoice_number' => 'required',
            'invoice_amount' => 'required|numeric',
            'received_date' => 'required|date',
            'items_json' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $items = json_decode($request->items_json, true);
            $poId = $request->po_id;

            // 1. Generate GRN Number
            $lastGrn = \App\Models\StmGrn::latest('id')->first();
            $nextNumber = 1;
            if ($lastGrn && $lastGrn->grn_number) {
                // Try to parse number
                $parts = explode('-', $lastGrn->grn_number);
                if (count($parts) > 1) {
                    $nextNumber = intval(end($parts)) + 1;
                } else {
                    $nextNumber = $lastGrn->id + 1;
                }
            } else if ($lastGrn) {
                $nextNumber = $lastGrn->id + 1;
            }
            $grnNumber = 'GRN-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // 2. Create GRN
            $grn = \App\Models\StmGrn::create([
                'supplier_id' => \App\Models\StmPurchaseOrder::find($poId)->supplier_id,
                'purchase_order_id' => $poId,
                'grn_number' => $grnNumber,
                'invoice_number' => $request->invoice_number,
                'invoice_amount' => $request->invoice_amount,
                'notes' => $request->notes,
                'is_completed' => ($request->overall_status == 'passed') ? 1 : 0,
                'is_active' => 1,
                'created_by' => Auth::id(),
            ]);

            // 3. Process Items
            $allCompleted = true;
            $hasReceiving = false;

            // 2.5 Generate Batch Number Logic Prep
            $lastStockIn = \App\Models\StmStockIn::whereNotNull('batch_number')
                ->where('batch_number', 'LIKE', 'BT%')
                ->latest('id')
                ->first();

            $currentBatchNum = 0;

            if ($lastStockIn) {
                // Extract number from BT0001
                $currentBatchNum = intval(substr($lastStockIn->batch_number, 2));
            }

            $auditQualitySummary = [];

            foreach ($items as $item) {
                $qtyReceived = floatval($item['actualReceived']);

                if ($qtyReceived > 0) {
                    $hasReceiving = true;

                    // Generate Batch Number for this item
                    $currentBatchNum++;
                    $batchNumber = 'BT' . str_pad($currentBatchNum, 4, '0', STR_PAD_LEFT);

                    // Map Quality Status
                    $qualityStatus = \App\CommonVariables::$passedQuality;
                    $auditStatusStr = 'Passed';

                    if (isset($item['qualityStatus'])) {
                        if ($item['qualityStatus'] == 'partial') {
                            $qualityStatus = \App\CommonVariables::$partiallyQuality;
                            $auditStatusStr = 'Partial';
                        } elseif ($item['qualityStatus'] == 'failed') {
                            $qualityStatus = \App\CommonVariables::$failedQuality;
                            $auditStatusStr = 'Failed';
                        }
                    }

                    $auditQualitySummary[] = ($item['productName'] ?? 'Item') . ": " . $auditStatusStr;

                    // Prepare optional fields ensuring null if empty, but keeping '0' for numbers
                    $notes = isset($item['qualityNotes']) ? trim($item['qualityNotes']) : null;
                    $notes = $notes === '' ? null : $notes;

                    $expiryPeriod = isset($item['expiryPeriod']) ? trim($item['expiryPeriod']) : null;
                    // Preserve '0' if it's a valid value, otherwise convert empty string to null
                    $expiryPeriod = ($expiryPeriod === '' || $expiryPeriod === null) ? null : $expiryPeriod;

                    $expiryDate = isset($item['expiryDate']) ? trim($item['expiryDate']) : null;
                    $expiryDate = $expiryDate === '' ? null : $expiryDate;

                    $mfgDate = isset($item['manufacturingDate']) ? trim($item['manufacturingDate']) : null;
                    $mfgDate = $mfgDate === '' ? null : $mfgDate;

                    // Create Stock In
                    $stockIn = \App\Models\StmStockIn::create([
                        'stm_grn_id' => $grn->id,
                        'pm_product_item_id' => $item['product_item_id'],
                        'added_quantity' => $qtyReceived,
                        'costing_price' => $item['costing_price'],
                        'selling_price' => $item['selling_price'],
                        'notes' => $notes,
                        'manufacturing_date' => $mfgDate,
                        'expiry_date' => $expiryDate,
                        'expire_period' => $expiryPeriod,
                        'batch_number' => $batchNumber,
                        'quality_check' => $qualityStatus,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    // Create Stock
                    $stock = \App\Models\StmStock::create([
                        'stm_stock_in_id' => $stockIn->id,
                        'pm_product_item_id' => $item['product_item_id'],
                        'stock_date' => $request->received_date,
                        'quantity' => $qtyReceived,
                        'costing_price' => $item['costing_price'],
                        'selling_price' => $item['selling_price'],
                        'notes' => $notes,
                        'manufacturing_date' => $mfgDate,
                        'expiry_date' => $expiryDate,
                        'expire_period' => $expiryPeriod,
                        'batch_number' => $batchNumber,
                        'quality_check' => $qualityStatus,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                    // Generate Barcodes
                    // Fetch product item to get ref_number_auto
                    $productItem = \App\Models\PmProductItem::find($item['product_item_id']);
                    $barcodeValue = $productItem->ref_number_auto ?? 'NO-REF-' . time();

                    // Loop for each unit received (rounded down to integer)
                    $loopCount = intval($qtyReceived);
                    for ($i = 0; $i < $loopCount; $i++) {
                        $barcode = \App\Models\StmBarcode::create([
                            'barcode' => $barcodeValue,
                            'stm_stock_id' => $stock->id,
                            'pm_product_item_id' => $item['product_item_id'],
                            'selling_price' => $item['selling_price'],
                            'is_sold' => 0,
                            'created_by' => Auth::id(),
                        ]);

                        // Create History
                        \App\Models\StmBarcodesHistory::create([
                            'barcode_id' => $barcode->id,
                            'created_by' => Auth::id(),
                            'action' => 'Generate',
                            'description' => "Generated via GRN {$grnNumber}",
                        ]);
                    }
                }

                // Update PO Item
                $poItem = \App\Models\StmPurchaseOrderHasProductItem::where('purchase_order_id', $poId)
                    ->where('product_item_id', $item['product_item_id'])
                    ->first();

                if ($poItem) {
                    $newTotalReceived = ($poItem->grn_received_quantity ?? 0) + $qtyReceived;
                    $isItemComplete = $newTotalReceived >= $poItem->quantity;

                    $poItem->update([
                        'grn_received_quantity' => $newTotalReceived,
                        'is_completed' => $isItemComplete ? 1 : 0
                    ]);
                }
            }

            // 4. Update PO Status
            $po = \App\Models\StmPurchaseOrder::find($poId);
            $oldStatus = $po->status;

            // Check if ALL items in PO are completed
            $dbItems = \App\Models\StmPurchaseOrderHasProductItem::where('purchase_order_id', $poId)->get();
            $poAllCompleted = $dbItems->every(function ($i) {
                return $i->is_completed == 1;
            });

            $newStatus = $oldStatus;
            if ($poAllCompleted) {
                $newStatus = CommonVariables::$received;
            } elseif ($hasReceiving) {
                if ($oldStatus < CommonVariables::$received) {
                    $newStatus = CommonVariables::$partiallyReceived;
                }
            }

            if ($newStatus != $oldStatus) {
                $po->status = $newStatus;
                $po->updated_by = Auth::id();
                $po->save();
            }

            // 5. Audit Log
            $auditDesc = "GRN {$grnNumber} created associated with this PO.";
            if (!empty($auditQualitySummary)) {
                $auditDesc .= " Quality: " . implode(', ', $auditQualitySummary);
            }
            $this->logAudit($poId, 'GRN Created', $auditDesc, $oldStatus, $newStatus);


            DB::commit();

            return response()->json(['success' => true, 'message' => 'GRN Created Successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error creating GRN: ' . $e->getMessage()], 500);
        }
    }

    private function logAudit($poId, $action, $description, $prevStatus, $newStatus)
    {
        $role = 'User';
        if (Auth::user()->user_role_id) {
            $userRole = \App\Models\PmUserRole::find(Auth::user()->user_role_id);
            if ($userRole) {
                $role = $userRole->user_role_name;
            }
        }

        \App\Models\StmPurchaseOrderAudit::create([
            'purchase_order_id' => $poId,
            'user_id' => Auth::id(),
            'user_role' => $role,
            'action' => $action,
            'description' => $description,
            'previous_status' => $prevStatus,
            'new_status' => $newStatus,
        ]);
    }

    public function grnIndex(Request $request)
    {
        $query = \App\Models\StmGrn::with([
            'supplier',
            'purchaseOrder',
            'stockIns.productItem.product',
            'stockIns.productItem.brand',
            'stockIns.productItem.variation',
            'stockIns.productItem.variationValue',
            'stockIns.stock.barcodes' // Eager load barcodes via Stock -> Barcodes relation? 
            // Wait, StmStockIn hasOne StmStock, StmStock hasMany StmBarcode.
            // Let's check relationships.
        ])->latest();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('grn_number', 'like', "%{$search}%")
                    ->orWhere('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // For the modal, we might need deep nested relations for barcodes.
        // StmGrn -> hasMany StmStockIn
        // StmStockIn -> hasOne StmStock (usually 1-to-1 created together)
        // StmStock -> hasMany StmBarcode
        // So 'stockIn.stock.barcodes' should work if relationships are defined in models.

        $grns = $query->paginate(10);

        return view('inventoryManagement.grnManage', compact('grns'));
    }

    public function inventoryMasterIndex(Request $request)
    {
        // 1. Base Query
        $query = \App\Models\PmProductItem::with([
            'product',
            'brand',
            'variation',
            'variationValue',
            'productTypes', // Functions as Category usually
            'stocks' // The relation we added
        ]);

        // 2. Filter: Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('ref_number_auto', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_code', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Filter: Category (Product Type)
        // 3. Filter: Category (Product Type)
        if ($request->has('category') && $request->category !== 'all') {
            // Robust ID-based filtering
            $query->whereHas('productTypes', function ($q) use ($request) {
                $q->where('pm_product_type.id', $request->category);
            });
        }

        // 4. Fetch Results
        $items = $query->get();

        // Fetch Product Types for Dropdown
        $productTypes = \App\Models\PmProductType::where('status', 1)->get();

        // 5. Process items for View (Calculate Stocks, Status, etc.)
        $processedProducts = $items->map(function ($item) use ($request) {

            // Determine which stocks to use based on filter
            $activeStocks = collect([]);
            $isBranchView = false;

            if ($request->has('location_type') && str_starts_with($request->location_type, 'branch-')) {
                // Branch Logic
                $branchId = str_replace('branch-', '', $request->location_type);
                $isBranchView = true;

                // Load branch stocks for this branch
                // Ideally this should be eagerly loaded with constraints, but for now lazy loading per item (less efficient but safer without changing base query structure too much)
                // Or we can use the relation loaded in the base query if we added it, but we haven't modified the base query widely yet.
                // Let's use lazy load for now.
                $item->load([
                    'branchStocks' => function ($q) use ($branchId) {
                        $q->where('um_branch_id', $branchId)->with('stock');
                    }
                ]);

                $activeStocks = $item->branchStocks;
            } else {
                // Warehouse Logic (Default)
                $activeStocks = $item->stocks;
            }

            $totalQty = $activeStocks->sum('quantity');

            // Value calc might differ slightly for branch if we use transfer price vs cost, 
            // but usually stock holding value is cost.
            // StmBranchStock has quantity, but price? The migration shows quantity, but cost might be on linked stm_stock.
            // Let's assume cost is from the linked stock record for now.
            $totalValue = $activeStocks->sum(function ($stockRecord) use ($isBranchView) {
                $qty = $stockRecord->quantity;
                $cost = 0;
                if ($isBranchView) {
                    // $stockRecord is StmBranchStock, need to access its related StmStock for costing_price
                    $cost = $stockRecord->stock->costing_price ?? 0;
                } else {
                    // $stockRecord is StmStock
                    $cost = $stockRecord->costing_price;
                }
                return $qty * $cost;
            });

            // Reorder Point - Placeholder or fetch if exists in PmProductItem
            // Assuming reorder_point column exists or defaulting
            $reorderPoint = $item->reorder_point ?? 100; // Default placeholder if not in DB
            $maxStock = $item->max_stock ?? 1000;

            // Determine Status
            $status = 'in-stock';
            if ($totalQty == 0) {
                $status = 'out-of-stock';
            } elseif ($totalQty < $reorderPoint) {
                $status = 'low-stock';
            }

            // Check expiry for 'expiring-soon' logic (e.g. within 30 days)
            $hasExpiring = $activeStocks->contains(function ($s) use ($isBranchView) {
                // Get expiry date from correct object
                $expiryDate = $isBranchView ? ($s->stock->expiry_date ?? null) : $s->expiry_date;
                return $expiryDate && \Carbon\Carbon::parse($expiryDate)->diffInDays(now()) < 30 && \Carbon\Carbon::parse($expiryDate)->isFuture();
            });
            if ($hasExpiring && $status !== 'out-of-stock') {
                $status = 'expiring-soon';
            }

            // Batches Logic
            $batches = $activeStocks->where('quantity', '>', 0)->map(function ($record) use ($isBranchView) {
                $expiryDate = null;
                $expirePeriod = null;
                $receivedDate = null;
                $batchNumber = 'N/A';

                if ($isBranchView) {
                    $expiryDate = $record->stock->expiry_date ?? null;
                    $expirePeriod = $record->stock->expire_period ?? null;
                    $receivedDate = $record->created_at;
                    $batchNumber = $record->stock->batch_number ?? 'N/A';
                } else {
                    $expiryDate = $record->expiry_date;
                    $expirePeriod = $record->expire_period;
                    $receivedDate = $record->stock_date;
                    $batchNumber = $record->batch_number;
                }

                $isExpiring = $expiryDate && \Carbon\Carbon::parse($expiryDate)->diffInDays(now()) < 30 && \Carbon\Carbon::parse($expiryDate)->isFuture();

                return [
                    'batchNumber' => $batchNumber,
                    'quantity' => $record->quantity,
                    'unit' => 'unit',
                    'qtyInUnit' => $record->qty_in_unit ?? 0,
                    'unitType' => $item->variationValue->unit_of_measurement_id ?? null,
                    'expiryDate' => $expiryDate,
                    'expirePeriod' => $expirePeriod,
                    'receivedDate' => $receivedDate,
                    'isExpiring' => $isExpiring // Flag for UI highlighting
                ];
            });


            return (object) [
                'id' => $item->id,
                'code' => $item->ref_number_auto ?? $item->reference_number,
                'name' => $item->product_name ?? ($item->product->product_name . ' ' . ($item->brand->brand_name ?? '')),
                'category' => $item->productTypes->first()->product_type_name ?? 'General',
                'section' => 'Main', // Placeholder
                'unit' => 'unit',
                'totalQuantity' => $totalQty,
                'reorderPoint' => $reorderPoint,
                'maxStock' => $maxStock,
                'status' => $status,
                // Unit cost average might be tricky with mixed batches, keeping simple average of loaded stocks
                'unitCost' => $totalQty > 0 ? ($totalValue / $totalQty) : 0,
                'totalValue' => $totalValue,
                'batches' => $batches // Pass batches instead of locations
            ];
        });

        // 6. Filter: Status (Post-calculation filter)
        if ($request->has('status') && $request->status !== 'all') {
            $processedProducts = $processedProducts->filter(function ($p) use ($request) {
                return $p->status === $request->status;
            });
        }

        // 7. Filter: Location (Placeholder logic)
        // If branch, we might filter OUT everything since we don't have branch stock data logic yet, 
        // OR just return empty list as products exist but stock is 0/unknown for branch.
        // For now, if branch is selected, we keep products but they will have 0 batches shown.

        // 8. Stats Calculation
        $stats = [
            'totalProducts' => $processedProducts->count(),
            'totalValue' => $processedProducts->sum('totalValue'),
            'lowStock' => $processedProducts->where('status', 'low-stock')->count(),
            'outOfStock' => $processedProducts->where('status', 'out-of-stock')->count(),
        ];

        // Fetch Branches
        $branches = \App\Models\UmBranch::where('status', 1)->get();

        return view('inventoryManagement.inventoryMaster', [
            'filteredProducts' => $processedProducts,
            'stats' => $stats,
            'filters' => $request->all(),
            'branches' => $branches,
            'productTypes' => $productTypes
        ]);
    }

    public function inventoryReportsAnalysisIndex()
    {
        return view('inventoryManagement.inventoryReportsAnalysis');
    }

    public function stockAdjustmentsIndex()
    {
        return view('inventoryManagement.stockAdjustments');
    }

    public function sectionOutletInventoryIndex()
    {
        $user = Auth::user();

        // 1. Fetch User's Branches
        $branches = $user->branches()->where('status', 1)->get();
        // Load departments for these branches
        $branches->load('departments');

        // unique departments across all user's branches
        $departments = $branches->pluck('departments')->flatten()->unique('id')->values();

        return view('inventoryManagement.SectionOutletInventory', compact('departments'));
    }

    public function getDepartmentStock(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'branch_id' => 'nullable' // Optional if we want to enforce branch context
        ]);

        $deptId = $request->department_id;
        $branchId = $request->branch_id ?? Auth::user()->current_branch_id;

        // Fetch Branch Stock for this Department
        // Assuming stm_branch_stock connects branch, department, and product
        // If pln_department_id is not in stm_branch_stock, we rely on branch context only?
        // Prompt implies Department has specific stock.
        // Let's assume stm_branch_stock has pln_department_id based on recent analysis of transfer logic (step 17, line 1487 has pln_department_id in create)

        $query = \App\Models\StmBranchStock::with(['productItem.product', 'productItem.brand', 'productItem.productTypes'])
            ->where('um_branch_id', $branchId)
            ->where('quantity', '>', 0);

        if ($deptId !== 'all') { // Optional 'all' logic if needed, but for now specific dept
            $query->where('pln_department_id', $deptId);
        }

        $stocks = $query->get();

        $items = $stocks->map(function ($stock) {
            $prod = $stock->productItem->product;
            $name = $prod->product_name ?? 'Unknown';
            if (isset($stock->productItem->brand->brand_name)) {
                $name .= ' - ' . $stock->productItem->brand->brand_name;
            }

            // Calculate Status
            $reorderPoint = $stock->productItem->reorder_point ?? 50; // Default
            $status = 'In Stock';
            $statusClass = 'bg-green-100 text-green-800';

            if ($stock->quantity == 0) {
                $status = 'Out of Stock';
                $statusClass = 'bg-red-100 text-red-800';
            } elseif ($stock->quantity < $reorderPoint) {
                $status = 'Low Stock';
                $statusClass = 'bg-yellow-100 text-yellow-800';
            }

            return [
                'id' => $stock->productItem->id,
                'name' => $name,
                'code' => $stock->productItem->ref_number_auto ?? $stock->productItem->reference_number,
                'category' => $stock->productItem->productTypes->first()->name ?? 'General',
                'quantity' => $stock->quantity,
                'reorder_point' => $reorderPoint,
                'value' => $stock->quantity * ($stock->costing_price ?? 0),
                'status' => $status,
                'status_class' => $statusClass,
                'unit' => 'unit' // placeholder
            ];
        });

        // Summary Stats
        $stats = [
            'stock_value' => $items->sum('value'),
            'item_count' => $items->count(),
            'low_stock' => $items->where('status', 'Low Stock')->count(),
            'out_of_stock' => $items->where('status', 'Out of Stock')->count() // Likely 0 since we filter qty > 0, but if we remove that filter...
        ];

        return response()->json([
            'items' => $items,
            'stats' => $stats
        ]);
    }

    public function warehouseManagementIndex()
    {
        return view('inventoryManagement.warehouseManagement');
    }

    public function updateTransferStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:stm_stock_order_request,id',
            'action' => 'required|in:Approved,Started,Completed,Rejected',
            'rejection_reason' => 'nullable|string'
        ]);

        $orderReq = \App\Models\StmStockOrderRequest::with('transfers')->find($request->id);
        $user = \App\Models\UmUser::first(); // Placeholder for logged-in user

        \DB::beginTransaction();
        try {
            $statusMap = [
                'Approved' => 1,
                'Started' => 2, // In-Transit
                'Completed' => 3,
                'Rejected' => 4
            ];
            $newStatus = $statusMap[$request->action];

            // 1. Update Order Request Status
            $orderReq->status = $newStatus;
            $orderReq->save();

            // 2. Log History
            // 2. Log History
            // Note: Detailed log for 'Approved' is handled inside the loop now.
            // For 'Started' or others, specific logs are good.
            // We can keep this generic log or make it conditional.
            // Let's modify it to be generic for status change unless overridden.
            if ($request->action !== 'Approved') { // Approved has detailed log
                \App\Models\StmStockOrderRequestHistory::create([
                    'order_request_id' => $orderReq->id,
                    'created_by' => $user->id,
                    'action' => $request->action,
                    'status' => $newStatus,
                    'description' => $request->rejection_reason ?? "Transfer marked as {$request->action}"
                ]);
            }

            // 3. Process Transfers Logic
            // 3. Process Transfers Logic
            $approvalSummary = [];
            foreach ($orderReq->transfers as $transfer) {
                if ($request->action === 'Approved') {

                    // Fetch Approved Qty from Payload
                    $approvedQty = $transfer->requesting_quantity; // Default
                    if ($request->has('items')) {
                        foreach ($request->items as $itemPayload) {
                            if ($itemPayload['id'] == $transfer->id) {
                                $approvedQty = intval($itemPayload['approved_qty']);
                                break;
                            }
                        }
                    }

                    // Enforce Max Qty
                    if ($approvedQty > $transfer->requesting_quantity) {
                        $approvedQty = $transfer->requesting_quantity;
                    }
                    if ($approvedQty < 0)
                        $approvedQty = 0;

                    $transfer->approved_quantity = $approvedQty;
                    $transfer->approved_date = now();
                    $transfer->approved_by = $user->id;
                    $transfer->save();

                    // PARTIAL APPROVAL / REFUND LOGIC
                    $diff = $transfer->requesting_quantity - $approvedQty;
                    if ($diff > 0) {
                        // 1. Refund Stock
                        $stockId = null;
                        if ($transfer->branch_stock_id) {
                            $stock = \App\Models\StmBranchStock::find($transfer->branch_stock_id);
                            if ($stock) {
                                $stock->quantity += $diff;
                                $stock->save();
                                $stockId = $stock->stm_stock_id ?? ($stock->stock->id ?? null);
                            }
                        } elseif ($transfer->stm_stock_id) {
                            $stock = \App\Models\StmStock::find($transfer->stm_stock_id);
                            if ($stock) {
                                $stock->quantity += $diff;
                                $stock->save();
                                $stockId = $stock->id;
                            }
                        }

                        // 2. Release Barcodes (Set order_request_id to NULL)
                        if ($stockId) {
                            $barcodesToRelease = \App\Models\StmBarcode::where('stm_stock_order_request_id', $orderReq->id)
                                ->where('stm_stock_id', $stockId)
                                // ->orderBy('id', 'desc') // LIFO? Or arbitrary.
                                ->limit($diff)
                                ->get();

                            foreach ($barcodesToRelease as $bc) {
                                $bc->stm_stock_order_request_id = null;
                                $bc->save();

                                // History
                                \App\Models\StmBarcodesHistory::create([
                                    'barcode_id' => $bc->id,
                                    'created_by' => $user->id,
                                    'action' => 'Transfer Partial Release',
                                    'description' => "Released from Transfer {$orderReq->order_number} due to partial approval",
                                ]);
                            }
                        }
                    }

                    $productName = $transfer->productItem->product_name ?? 'Item';
                    $approvalSummary[] = "{$productName}: Req {$transfer->requesting_quantity}, Appr {$approvedQty}" . ($diff > 0 ? " (Rejected {$diff})" : "");


                } elseif ($request->action === 'Started') {
                    // START TRANSFER logic
                    $transfer->dispatched_quantity = $transfer->approved_quantity;
                    $transfer->dispatched_date = now();
                    $transfer->dispatched_by = $user->id;
                    $transfer->save();

                    // Create In-Transit Stock at Destination (Branch)
                    // Status = 0 (In-Transfer / Pending Reception)
                    if ($orderReq->um_branch_id) {
                        // Resolve parent stock ID
                        $parentStockId = null;
                        if ($transfer->stm_stock_id) {
                            $parentStockId = $transfer->stm_stock_id; // From Warehouse
                        } elseif ($transfer->branch_stock_id) {
                            // From Branch -> Get its parent stock
                            $srcStock = \App\Models\StmBranchStock::find($transfer->branch_stock_id);
                            $parentStockId = $srcStock->stm_stock_id;
                        }

                        if ($parentStockId) {
                            \App\Models\StmBranchStock::create([
                                'pm_product_item_id' => $transfer->pm_product_item_id,
                                'um_branch_id' => $orderReq->um_branch_id,
                                'pln_department_id' => $orderReq->pln_department_id, // Add Dept
                                'stm_stock_id' => $parentStockId,
                                'stm_stock_transfer_id' => $transfer->id,
                                'quantity' => $transfer->approved_quantity,
                                'status' => 0, // 0 = In Transit / Pending
                                'created_by' => $user->id,
                                'updated_by' => $user->id
                            ]);
                        }
                    }

                } elseif ($request->action === 'Completed') {
                    // 1. Update Transfer
                    $receivedQty = $item['received_qty'] ?? $transfer->dispatched_quantity;
                    $transfer->received_quantity = $receivedQty;
                    $transfer->received_date = now();
                    $transfer->received_by = $user->id;
                    $transfer->save();

                    // 2. Activate Branch Stock (Destination)
                    // Note: pln_department_id should already be set during creation in 'Started' phase.
                    // But if it wasn't set then, we can update it now.
                    $dstStock = \App\Models\StmBranchStock::where('stm_stock_transfer_id', $transfer->id)->first();
                    if ($dstStock) {
                        $dstStock->status = 1; // Active
                        $dstStock->quantity = $receivedQty; // Update to actual received
                        $dstStock->pln_department_id = $orderReq->pln_department_id; // Ensure Dept is set/updated
                        $dstStock->save();
                    }

                    // 3. Reassign Barcodes
                    // Find barcodes linked to this request and source stock
                    $lookupStockId = null;
                    if ($transfer->stm_stock_id) {
                        $lookupStockId = $transfer->stm_stock_id;
                    } elseif ($transfer->branch_stock_id) {
                        // Determine parent id from branch stock
                        $s = \App\Models\StmBranchStock::find($transfer->branch_stock_id);
                        $lookupStockId = $s->stm_stock_id ?? ($s->stock->id ?? null);
                    }

                    if ($lookupStockId) {
                        $barcodesToReceive = \App\Models\StmBarcode::where('stm_stock_order_request_id', $orderReq->id)
                            ->where('stm_stock_id', $lookupStockId)
                            ->orderBy('id')
                            ->take($receivedQty)
                            ->get();

                        foreach ($barcodesToReceive as $bc) {
                            $bc->um_branch_id = $orderReq->um_branch_id;
                            $bc->pln_department_id = $orderReq->pln_department_id;
                            $bc->stm_stock_order_request_id = null; // Unlink
                            $bc->save();

                            // History
                            \App\Models\StmBarcodesHistory::create([
                                'barcode_id' => $bc->id,
                                'created_by' => $user->id,
                                'action' => 'Transfer Received',
                                'description' => "Received at Branch ID: {$orderReq->um_branch_id}",
                                'um_branch_id' => $orderReq->um_branch_id // Record location in history too if column exists, else just helper
                            ]);
                        }
                    }

                    $productName = $transfer->productItem->product_name ?? 'Item';
                    $approvalSummary[] = "{$productName}: Received {$receivedQty}";
                } elseif ($request->action === 'Rejected') {
                    // REFUND: Add Stock Back
                    $qtyToRefund = $transfer->requesting_quantity; // Refund what was requested/deducted

                    // Barcode Logic for Full Rejection
                    $stockId = null;

                    if ($transfer->branch_stock_id) {
                        $stock = \App\Models\StmBranchStock::find($transfer->branch_stock_id);
                        if ($stock) {
                            $stock->quantity += $qtyToRefund;
                            $stock->save();
                            $stockId = $stock->stm_stock_id ?? ($stock->stock->id ?? null);
                        }
                    } elseif ($transfer->stm_stock_id) {
                        $stock = \App\Models\StmStock::find($transfer->stm_stock_id);
                        if ($stock) {
                            $stock->quantity += $qtyToRefund;
                            $stock->save();
                            $stockId = $stock->id;
                        }
                    }

                    // Release ALL Barcodes
                    if ($stockId) {
                        $barcodesToRelease = \App\Models\StmBarcode::where('stm_stock_order_request_id', $orderReq->id)
                            ->where('stm_stock_id', $stockId)
                            ->get();

                        foreach ($barcodesToRelease as $bc) {
                            $bc->stm_stock_order_request_id = null;
                            $bc->save();

                            \App\Models\StmBarcodesHistory::create([
                                'barcode_id' => $bc->id,
                                'created_by' => $user->id,
                                'action' => 'Transfer Rejected',
                                'description' => "Released from Transfer {$orderReq->order_number} due to Rejection",
                            ]);
                        }
                    }
                }
            }

            // Update History Description if Approved
            if ($request->action === 'Approved' && !empty($approvalSummary)) {
                $historyDescription = implode(', ', $approvalSummary);
                // Update the history record created at the top? Or create new one?
                // The one created at line ~1351 used default description.
                // Let's create another one or update it. 
                // Since we are inside transaction, we can update the one we just created if we had handle to it.
                // But simply creating another detailed log might be cleaner, OR we can fetch the last history.

                // Let's just create a more detailed log entry specifically for the approval details if needed, 
                // or acceptable to just let the standard log be "Transfer marked as Approved" and rely on this new logic?
                // "confirm in stm_stock_order_request_history description record how qty approve and reject"
                // I will UPDATE the history record created at the start of method? No I don't have $history variable.
                // I will create a specific entry for "Approval Details".

                \App\Models\StmStockOrderRequestHistory::create([
                    'order_request_id' => $orderReq->id,
                    'created_by' => $user->id,
                    'action' => 'Approval Details', // Or just combine with Approved
                    'status' => $newStatus,
                    'description' => "Approval Breakdown: " . $historyDescription
                ]);
            }

            \DB::commit();
            return response()->json(['success' => true, 'message' => "Transfer {$request->action} successfully"]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function storeTransfer(Request $request)
    {
        $request->validate([
            'source_type' => 'required',
            'source_id' => 'required',
            'destination_type' => 'required',
            'destination_id' => 'required',
            'items' => 'required|array|min:1',
            'scheduled_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            // Priority Mapping
            $pMap = ['low' => 1, 'medium' => 2, 'high' => 3, 'urgent' => 4];
            $priority = $pMap[$request->priority] ?? 2;

            // Generate Order Number
            $orderNo = 'TRF-' . strtoupper(uniqid());

            // Determine IDs
            $reqBranchId = ($request->destination_type == 'branch') ? $request->destination_id : null;
            $reqDeptId = ($request->destination_type == 'department') ? $request->destination_id : null;

            if ($request->destination_type == 'department') {
                $deptBranch = DB::table('um_branch_has_department')->where('department_id', $reqDeptId)->first();
                $reqBranchId = $deptBranch ? $deptBranch->um_branch_id : null;
            }

            $srcBranchId = ($request->source_type == 'branch' || $request->source_type == 'warehouse') ? $request->source_id : null;
            $srcDeptId = ($request->source_type == 'department') ? $request->source_id : null;

            if ($request->source_type == 'department') {
                $srcBranch = DB::table('um_branch_has_department')->where('department_id', $srcDeptId)->first();
                $srcBranchId = $srcBranch ? $srcBranch->um_branch_id : null;
            }

            // Create Request
            $orderRequest = new \App\Models\StmStockOrderRequest();
            $orderRequest->order_number = $orderNo;
            $orderRequest->um_branch_id = $reqBranchId;
            $orderRequest->pln_department_id = $reqDeptId;

            $orderRequest->req_from_branch_id = $srcBranchId;
            $orderRequest->req_from_department_id = $srcDeptId;

            $orderRequest->is_active = 1;
            $orderRequest->status = \App\CommonVariables::$orderRequestPending;
            $orderRequest->priority_level = $priority;
            $orderRequest->notes = $request->notes;
            $orderRequest->scheduled_date = $request->scheduled_date;
            $orderRequest->created_by = $user->id;
            $orderRequest->updated_by = $user->id;
            $orderRequest->save();

            // Create History
            $history = new \App\Models\StmStockOrderRequestHistory();
            $history->order_request_id = $orderRequest->id;
            $history->created_by = $user->id;
            $history->action = 'Created';
            $history->status = \App\CommonVariables::$orderRequestPending;
            $history->description = 'Stock transfer request created.';
            $history->save();

            // Create Items
            foreach ($request->items as $item) {
                $transfer = new \App\Models\StmStockTransfer();
                $transfer->pm_product_item_id = $item['product_item_id'];
                $transfer->stm_stock_order_request_id = $orderRequest->id;

                if ($request->source_type == 'warehouse') {
                    $transfer->stm_stock_id = $item['stock_id'];
                    $transfer->branch_stock_id = null;

                    // DEDUCT STOCK (Warehouse)
                    $stock = \App\Models\StmStock::find($item['stock_id']);
                    if (!$stock) { // Should check existence
                        throw new \Exception("Stock record not found for Item ID: {$item['product_item_id']}");
                    }
                    if ($stock->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock in Warehouse for Item ID: {$item['product_item_id']}. Available: {$stock->quantity}, Requested: {$item['quantity']}");
                    }
                    $stock->quantity -= $item['quantity'];
                    $stock->save();


                } else {
                    $transfer->branch_stock_id = $item['stock_id'];
                    $transfer->stm_stock_id = null;

                    // DEDUCT STOCK (Branch)
                    $stock = \App\Models\StmBranchStock::find($item['stock_id']);
                    if (!$stock) {
                        throw new \Exception("Stock record not found for Item ID: {$item['product_item_id']}");
                    }
                    if ($stock->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock in Branch for Item ID: {$item['product_item_id']}. Available: {$stock->quantity}, Requested: {$item['quantity']}");
                    }
                    $stock->quantity -= $item['quantity'];
                    $stock->save();
                }

                $transfer->batch_number = $item['batch'];
                $transfer->requesting_quantity = $item['quantity'];

                $transfer->approved_quantity = null;
                $transfer->approved_date = null;
                $transfer->approved_by = null;
                $transfer->dispatched_quantity = null;
                $transfer->dispatched_date = null;
                $transfer->dispatched_by = null;
                $transfer->received_quantity = null;
                $transfer->received_date = null;
                $transfer->received_by = null;

                $transfer->save();

                // --- ASSIGN BARCODES ---
                $lookupStockId = null;
                if ($request->source_type == 'warehouse') {
                    $lookupStockId = $item['stock_id'];
                } else {
                    // For Branch, fetch parent Stock ID
                    // $stock was found above (StmBranchStock)
                    $lookupStockId = $stock->stm_stock_id ?? ($stock->stock->id ?? null);
                }

                if ($lookupStockId) {
                    // Fetch available barcodes
                    $barcodes = \App\Models\StmBarcode::where('stm_stock_id', $lookupStockId)
                        ->where('is_sold', 0)
                        ->whereNull('stm_stock_order_request_id')
                        ->orderBy('id') // FIFO
                        ->take($item['quantity'])
                        ->get();

                    foreach ($barcodes as $barcode) {
                        $barcode->stm_stock_order_request_id = $orderRequest->id;
                        $barcode->save();

                        // Record History
                        \App\Models\StmBarcodesHistory::create([
                            'barcode_id' => $barcode->id,
                            'created_by' => $user->id,
                            'action' => 'Transfer Assigned',
                            'description' => "Assigned to Transfer Request {$orderRequest->order_number}",
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'order_number' => $orderNo]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
