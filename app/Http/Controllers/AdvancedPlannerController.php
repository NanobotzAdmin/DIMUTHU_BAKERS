<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlnDepartment;
use App\Models\PlnResource;
use App\Models\PlnProductionSchedule;
use App\Models\StmOrderRequest;
use App\Models\UmUser;
use App\Models\StmBranchStock;
use App\Models\StmStock;
use App\Models\StmBarcode;
use App\CommonVariables;
use Carbon\Carbon;

class AdvancedPlannerController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $currentBranchId = $user->current_branch_id;

            // Treat NULL as Warehouse (-1) if the logic dictates Warehouse = Null Branch
            // If current_branch_id is strictly null, we assume Warehouse view
            if (is_null($currentBranchId)) {
                $currentBranchId = -1;
            }

            // Initialize the query
            $query = PlnDepartment::where('status', 1);

            if ($currentBranchId !== null) { // -1 or ID (0 is not expected for branch IDs)
                // Handle Warehouse (-1) vs Specific Branch
                if ($currentBranchId == -1) {
                    // Warehouse: Filter Departments linked to NULL branch in pivot
                    $query->whereExists(function ($sq) {
                        $sq->select(\Illuminate\Support\Facades\DB::raw(1))
                            ->from('um_branch_has_department')
                            ->whereColumn('um_branch_has_department.pln_department_id', 'pln_departments.id')
                            ->whereNull('um_branch_has_department.um_branch_id');
                    });
                } else {
                    // Specific Branch
                    $query->whereHas('branches', function ($q) use ($currentBranchId) {
                        $q->where('um_branch.id', $currentBranchId);
                    });
                }

                // Add robust resource filtering (same as before) logic inside here is implicit if we structured it right
                // But wait, the previous tool replaced the whole query structure.
                // I need to make sure I don't lose the 'with resources' logic.
                // The previous step embedded 'with resources' inside the if blocks BUT
                // I need to re-apply the correct with() closure based on $currentBranchId.

                $query->with([
                    'resources' => function ($q) use ($currentBranchId) {
                        if ($currentBranchId == -1) {
                            $q->whereExists(function ($sq) {
                                $sq->select(\Illuminate\Support\Facades\DB::raw(1))
                                    ->from('um_branch_has_resources')
                                    ->whereColumn('um_branch_has_resources.pln_resource_id', 'pln_resources.id')
                                    ->whereNull('um_branch_has_resources.um_branch_id');
                            });
                        } else {
                            $q->whereHas('branches', function ($bq) use ($currentBranchId) {
                                $bq->where('um_branch.id', $currentBranchId);
                            });
                        }

                        $q->with([
                            'schedules' => function ($sq) {
                                $sq->where('start_time', '>=', now()->subDays(30));
                            }
                        ]);
                    }
                ]);

            } else {
                // This block acts as fallback if we decided NULL != Warehouse, but we did above.
                // So effectively this might be dead code if NULL -> -1.
                // Unless $currentBranchId can be 0? 
                // Let's keep it safe.
                $userBranchIds = $user->branches()->pluck('um_branch.id');
                $query = PlnDepartment::with([
                    'resources' => function ($q) use ($userBranchIds) {
                        $q->whereHas('branches', function ($bq) use ($userBranchIds) {
                            $bq->whereIn('um_branch.id', $userBranchIds);
                        })->with([
                                    'schedules' => function ($sq) {
                                        $sq->where('start_time', '>=', now()->subDays(30));
                                    }
                                ]);
                    }
                ])->where('status', 1)
                    ->whereHas('branches', function ($q) use ($userBranchIds) {
                        $q->whereIn('um_branch.id', $userBranchIds);
                    });
            }
        } else {
            // Fallback for non-authed (shouldn't happen)
            $query = PlnDepartment::with('resources.schedules')->where('status', 1);
        }

        $departmentsRaw = $query->get();

        // Convert to array format for Timeline view
        $departments = $departmentsRaw->map(function ($dept) {
            // No need for further PHP filtering here as Query handles it
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'color' => $dept->color ?? 'blue',
                'icon' => $dept->icon ?? 'box',
                'expanded' => true,
                'resources' => $dept->resources->map(function ($res) {
                    return [
                        'id' => $res->id,
                        'name' => $res->name,
                        'type' => $res->type,
                        'capacity' => $res->capacity,
                        'currentUtilization' => 0,
                        'status' => $res->status == 1 ? 'active' : 'inactive',
                        'events' => $res->schedules->map(function ($sch) {
                            return [
                                'id' => $sch->id,
                                'resourceId' => $sch->pln_resource_id,
                                'title' => $sch->notes ?? 'Production',
                                'start' => $sch->start_time->toIso8601String(),
                                'end' => $sch->end_time->toIso8601String(),
                                'extendedProps' => [
                                    'orderId' => $sch->stm_order_request_id,
                                    'productItemId' => $sch->pm_product_item_id,
                                    'quantity' => $sch->quantity,
                                    'status' => $sch->status,
                                    'userId' => $sch->user_id
                                ]
                            ];
                        })
                    ];
                })
            ];
        });

        // --- NEW: User Branches for Dropdown ---
        $userBranches = collect();
        if (auth()->check()) {
            // Fetch User's Branches
            $userBranches = auth()->user()->branches()->get(['um_branch.id', 'um_branch.name']);

            // Add "Warehouse" manually
            $userBranches->prepend([
                'id' => -1,
                'name' => 'Warehouse'
            ]);
        }

        // Fetch Staff
        $staff = UmUser::where('is_active', 1)
            ->get(['id', 'first_name', 'last_name'])
            ->map(function ($u) {
                return ['id' => $u->id, 'name' => "{$u->first_name} {$u->last_name}"];
            });

        // Status Map
        $statuses = [
            ['value' => CommonVariables::$productionScheduled, 'label' => 'Scheduled'],
            ['value' => CommonVariables::$productionInProgress, 'label' => 'In Progress'],
            ['value' => CommonVariables::$productionCompleted, 'label' => 'Completed'],
            ['value' => CommonVariables::$productionDelayed, 'label' => 'Delayed'],
        ];

        // 2. Fetch Orders that need scheduling
        $ordersQuery = StmOrderRequest::with(['customer', 'orderProducts.productItem'])
            ->where('status', CommonVariables::$orderRequestApproved);

        // Filter Orders by Current Branch if set
        if (auth()->check()) {
            // Using same $currentBranchId logic derived above (Null -> -1)
            // But we need to be careful if we didn't persist $currentBranchId var scope from previous block.
            // Let's re-derive safely or just use the same logic.
            // Assuming $currentBranchId was set in the block above. Ideally we should have defined it outside the if.
            // Let's re-access user->current_branch_id and apply same logic.

            $cBranchId = auth()->user()->current_branch_id;
            if (is_null($cBranchId)) {
                $cBranchId = -1;
            }

            if ($cBranchId == -1) {
                // Warehouse Orders: Assuming req_from_branch_id is NULL for Warehouse based on pattern
                $ordersQuery->whereNull('req_from_branch_id');
            } else {
                $ordersQuery->where('req_from_branch_id', $cBranchId);
            }
        }

        $orders = $ordersQuery->orderBy('delivery_date', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer ? $order->customer->name : 'Walk-in',
                    'delivery_date' => $order->delivery_date,
                    'status' => $order->status,
                    'products' => $order->orderProducts->map(function ($op) {
                        return [
                            'product_name' => $op->productItem->product_name ?? 'Unknown',
                            'quantity' => $op->quantity,
                            'product_item_id' => $op->pm_product_item_id,
                            'unit_price' => $op->unit_price
                        ];
                    })
                ];
            });

        return view('productionManagement.AdvancedPlanner', compact('departments', 'departmentsRaw', 'orders', 'staff', 'statuses', 'userBranches'));
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'resource_id' => 'required|exists:pln_resources,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'order_id' => 'nullable|exists:stm_order_requests,id',
            'product_item_id' => 'nullable|exists:pm_product_item,id',
            'quantity' => 'nullable|numeric',
            'branch_id' => 'nullable',
            'pln_department_id' => 'nullable|exists:pln_departments,id'
        ]);

        try {
            // Determine Branch ID for DB (Handle -1 for Warehouse)
            $branchIdInput = $request->input('branch_id');
            $dbBranchId = ($branchIdInput == -1) ? null : ($branchIdInput ?: auth()->user()->current_branch_id);
            $deptId = $request->input('pln_department_id');
            // Check if this is a bulk schedule request for an Order (Order ID present, Product ID null)
            if ($request->filled('order_id') && !$request->filled('product_item_id')) {
                $order = StmOrderRequest::with([
                    'orderProducts.productItem.recipe.ingredients.productItem.variationValue'
                ])->findOrFail($request->order_id);

                // Validate Stock Availability
                foreach ($order->orderProducts as $op) {
                    $productItem = $op->productItem;
                    $recipe = $productItem ? $productItem->recipe : null;
                    if ($recipe) {
                        foreach ($recipe->ingredients as $ing) {
                            $requiredPerUnit = $ing->quantity;
                            // Calculate Total Required in Base Unit (g/ml/piece)
                            $totalRequiredBase = $requiredPerUnit * $op->quantity;

                            $ingUnit = strtolower($ing->unit);
                            // Normalize to base unit
                            if ($ingUnit === 'kg' || $ingUnit === 'l') {
                                $totalRequiredBase *= 1000;
                            }

                            $availableBase = 0;
                            if ($ing->product_item_id) {
                                $query = StmBranchStock::where('pm_product_item_id', $ing->product_item_id);
                                if ($dbBranchId) {
                                    $query->where('um_branch_id', $dbBranchId);
                                } else {
                                    $query->whereNull('um_branch_id');
                                }
                                // Use qty_in_unit directly (already in base unit)
                                $availableBase = $query->sum('qty_in_unit');
                            }

                            // Allow small float difference tolerance if needed, but strict check is fine for now
                            if ($availableBase < $totalRequiredBase) {
                                return response()->json([
                                    'success' => false,
                                    'code' => 'INSUFFICIENT_STOCK',
                                    'message' => 'Insufficient stock for ingredient: ' . $ing->ingredient_name .
                                        '. Required: ' . $totalRequiredBase . ' (base), Available: ' . $availableBase . ' (base).',
                                    'order_id' => $request->order_id
                                ]);
                            }
                        }
                    }
                }
                $createdSchedules = [];

                foreach ($order->orderProducts as $op) {
                    $createdSchedules[] = PlnProductionSchedule::create([
                        'pln_resource_id' => $request->resource_id,
                        'stm_order_request_id' => $request->order_id,
                        'pm_product_item_id' => $op->pm_product_item_id,
                        'start_time' => Carbon::parse($request->start_time),
                        'end_time' => Carbon::parse($request->end_time),
                        'quantity' => $op->quantity,
                        'status' => CommonVariables::$productionScheduled,
                        'notes' => $op->productItem->product_name ?? 'Production',
                        'created_by' => auth()->id(),
                        'um_branch_id' => $dbBranchId,
                        'pln_department_id' => $deptId
                    ]);
                }

                // --- Deduct Stock & Update Barcodes ---
                foreach ($order->orderProducts as $op) {
                    $productItem = $op->productItem;
                    if ($productItem && $productItem->recipe) {
                        foreach ($productItem->recipe->ingredients as $ing) {
                            // Calculate Total Required in Base Unit
                            $requiredPerUnit = $ing->quantity;
                            $totalRequiredBase = $requiredPerUnit * $op->quantity;

                            $rUnit = strtolower($ing->unit);
                            if ($rUnit === 'kg' || $rUnit === 'l') {
                                $totalRequiredBase *= 1000;
                            }

                            // Calculate Pack Base Content
                            $packContentBase = 1;

                            if ($ing->productItem && $ing->productItem->variationValue) {
                                $val = $ing->productItem->variationValue;
                                $vVal = is_numeric($val->variation_value) ? (float) $val->variation_value : 1;

                                $sUnitId = $val->unit_of_measurement_id;
                                $sFactor = ($sUnitId == 3 || $sUnitId == 4) ? 1000 : 1;

                                $packContentBase = $vVal * $sFactor;
                            }

                            // Calculate Needed Packs
                            $neededPacks = $packContentBase > 0 ? ($totalRequiredBase / $packContentBase) : 0;

                            // Fetch Stock (FIFO)
                            if ($ing->product_item_id && $neededPacks > 0) {
                                $query = StmBranchStock::where('pm_product_item_id', $ing->product_item_id)
                                    ->where('quantity', '>', 0)
                                    ->orderBy('created_at', 'asc');

                                if ($dbBranchId) {
                                    $query->where('um_branch_id', $dbBranchId);
                                } else {
                                    $query->whereNull('um_branch_id');
                                }

                                $branchStocks = $query->get();

                                foreach ($branchStocks as $bs) {
                                    if ($neededPacks <= 0)
                                        break;

                                    $deduct = min($bs->quantity, $neededPacks);

                                    // Decrement Branch Stock
                                    $bs->quantity -= $deduct;
                                    $bs->save(); // Trigger CalculatesQtyInUnit

                                    // Decrement Master Stock
                                    if ($bs->stm_stock_id) {
                                        $masterStock = StmStock::find($bs->stm_stock_id);
                                        if ($masterStock) {
                                            $masterStock->quantity -= $deduct;
                                            $masterStock->save(); // Trigger CalculatesQtyInUnit

                                            // Update Barcodes
                                            $barcodesToUpdate = floor($deduct);
                                            if ($barcodesToUpdate > 0) {
                                                $barcodes = StmBarcode::where('stm_stock_id', $masterStock->id)
                                                    ->whereNull('stm_order_requests_id')
                                                    ->limit((int) $barcodesToUpdate)
                                                    ->get();

                                                foreach ($barcodes as $bc) {
                                                    $bc->stm_order_requests_id = $order->id;
                                                    $bc->save(); // Trigger CalculatesQtyInUnit if needed
                                                }
                                            }
                                        }
                                    }

                                    $neededPacks -= $deduct;
                                }
                            }
                        }
                    }
                }

                // Update Order Status
                $order->update(['status' => CommonVariables::$orderRequestProductionStarted]);

                return response()->json([
                    'success' => true,
                    'message' => 'Schedules created for ' . count($createdSchedules) . ' products!',
                    'schedules' => $createdSchedules // Return plural
                ]);

            } else {
                // Single Create (Legacy or Specific Product)
                $schedule = PlnProductionSchedule::create([
                    'pln_resource_id' => $request->resource_id,
                    'stm_order_request_id' => $request->order_id,
                    'pm_product_item_id' => $request->product_item_id,
                    'start_time' => Carbon::parse($request->start_time),
                    'end_time' => Carbon::parse($request->end_time),
                    'quantity' => $request->quantity ?? 0,
                    'status' => CommonVariables::$productionScheduled,
                    'notes' => $request->notes,
                    'created_by' => auth()->id(),
                    'um_branch_id' => $dbBranchId,
                    'pln_department_id' => $deptId
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Schedule created successfully!',
                    'schedule' => $schedule // Return singular (frontend needs to handle both or we standardize)
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetchBranchDepartments(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (!$branchId) {
            // Fallback to user's current branch if not specified
            $user = auth()->user();
            $branchId = $user ? $user->current_branch_id : null;
        }

        if (!$branchId) {
            return response()->json([], 400); // Bad Request if no branch
        }

        if ($branchId == -1) {
            // --- WAREHOUSE DEPARTMENTS ---
            // Fetch relevant department IDs from pivot table where um_branch_id is NULL
            $deptIds = \Illuminate\Support\Facades\DB::table('um_branch_has_department')
                ->whereNull('um_branch_id')
                ->pluck('department_id');

            $departments = PlnDepartment::whereIn('id', $deptIds)
                ->where('status', 1)
                ->get(['id', 'name']);

        } else {
            // --- BRANCH DEPARTMENTS ---
            $departments = \App\Models\UmBranch::find($branchId)
                ->departments()
                ->where('pln_departments.status', 1)
                ->get(['pln_departments.id', 'pln_departments.name']);
        }

        return response()->json($departments);
    }

    public function fetchTimelineData(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (!$branchId) {
            return response()->json([], 400);
        }

        // 1. Fetch Departments and their Resources with Schedules
        $query = PlnDepartment::with([
            'resources' => function ($q) use ($branchId) {
                // Filter Resources
                if ($branchId == -1) {
                    $q->whereExists(function ($sq) {
                        $sq->select(\Illuminate\Support\Facades\DB::raw(1))
                            ->from('um_branch_has_resources')
                            ->whereColumn('um_branch_has_resources.pln_resource_id', 'pln_resources.id')
                            ->whereNull('um_branch_has_resources.um_branch_id');
                    });
                } else {
                    $q->whereHas('branches', function ($bq) use ($branchId) {
                        $bq->where('um_branch.id', $branchId);
                    });
                }

                $q->with([
                    'schedules' => function ($sq) {
                        $sq->where('start_time', '>=', now()->subDays(30));
                    }
                ]);
            }
        ])->where('status', 1);

        // Filter Departments
        if ($branchId == -1) {
            $query->whereExists(function ($sq) {
                $sq->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('um_branch_has_department')
                    ->whereColumn('um_branch_has_department.department_id', 'pln_departments.id')
                    ->whereNull('um_branch_has_department.um_branch_id');
            });
        } else {
            $query->whereHas('branches', function ($q) use ($branchId) {
                $q->where('um_branch.id', $branchId);
            });
        }

        $departmentsRaw = $query->get();

        // Format Departments
        $departments = $departmentsRaw->map(function ($dept) {
            return [
                'id' => $dept->id,
                'name' => $dept->name,
                'color' => $dept->color ?? 'blue',
                'icon' => $dept->icon ?? 'box',
                'expanded' => true,
                'resources' => $dept->resources->map(function ($res) {
                    return [
                        'id' => $res->id,
                        'name' => $res->name,
                        'type' => $res->type,
                        'capacity' => $res->capacity,
                        'currentUtilization' => 0,
                        'status' => $res->status == 1 ? 'active' : 'inactive',
                        'events' => $res->schedules->map(function ($sch) {
                            return [
                                'id' => $sch->id,
                                'resourceId' => $sch->pln_resource_id,
                                'title' => $sch->notes ?? 'Production',
                                'start' => $sch->start_time->toIso8601String(),
                                'end' => $sch->end_time->toIso8601String(),
                                'extendedProps' => [
                                    'orderId' => $sch->stm_order_request_id,
                                    'productItemId' => $sch->pm_product_item_id,
                                    'quantity' => $sch->quantity,
                                    'status' => $sch->status,
                                    'userId' => $sch->user_id
                                ]
                            ];
                        })
                    ];
                })->values()
            ];
        });

        // 2. Fetch Orders
        $ordersQuery = StmOrderRequest::with(['customer', 'orderProducts.productItem'])
            ->where('status', CommonVariables::$orderRequestApproved);

        if ($branchId == -1) {
            $ordersQuery->whereNull('req_from_branch_id');
        } else {
            $ordersQuery->where('req_from_branch_id', $branchId);
        }

        $orders = $ordersQuery->orderBy('delivery_date', 'asc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer ? $order->customer->name : 'Walk-in',
                    'delivery_date' => $order->delivery_date,
                    'status' => $order->status,
                    'products' => $order->orderProducts->map(function ($op) {
                        return [
                            'product_name' => $op->productItem->product_name ?? 'Unknown',
                            'quantity' => $op->quantity,
                            'product_item_id' => $op->pm_product_item_id,
                            'unit_price' => $op->unit_price
                        ];
                    })
                ];
            });

        return response()->json([
            'departments' => $departments,
            'orders' => $orders
        ]);
    }

    public function fetchResources(Request $request)
    {
        $branchId = $request->input('branch_id');
        $deptId = $request->input('department_id');

        if (!$branchId)
            return response()->json([]);

        // Fetch Resources Linked to this Branch & Department
        $query = \Illuminate\Support\Facades\DB::table('um_branch_has_resources')
            ->join('pln_resources', 'um_branch_has_resources.pln_resource_id', '=', 'pln_resources.id')
            ->join('pln_departments', 'um_branch_has_resources.pln_department_id', '=', 'pln_departments.id')
            ->where('pln_resources.status', 1);

        if ($deptId && $deptId !== 'all') {
            $query->where('um_branch_has_resources.pln_department_id', $deptId);
        }

        if ($branchId == -1) {
            $query->whereNull('um_branch_has_resources.um_branch_id');
        } else {
            $query->where('um_branch_has_resources.um_branch_id', $branchId);
        }

        $resources = $query->select(
            'pln_resources.id',
            'pln_resources.name',
            'pln_resources.type',
            'pln_resources.capacity',
            'pln_resources.status',
            'pln_departments.id as department_id',
            'pln_departments.name as department_name'
        )->get();

        return response()->json($resources);
    }

    public function searchResources(Request $request)
    {
        $search = $request->query('q');
        if (!$search)
            return response()->json([]);

        $resources = PlnResource::where('name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'type', 'capacity', 'pln_department_id']); // Fetch department ID too to auto-select

        return response()->json($resources);
    }



    public function storeResource(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:100',
            'capacity' => 'nullable|numeric|min:0',
            'pln_department_id' => 'required|exists:pln_departments,id',
            'branch_id' => 'required'
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $user = auth()->user();
            // Use submitted branch_id, handle -1 for Warehouse
            $branchId = $request->branch_id;
            $dbBranchId = ($branchId == -1) ? null : $branchId;

            // 1. Check/Create Master Resource
            $resource = PlnResource::where('name', $request->name)->first();

            if (!$resource) {
                $resource = PlnResource::create([
                    'pln_department_id' => $request->pln_department_id,
                    'name' => $request->name,
                    'type' => $request->type,
                    'capacity' => $request->capacity ?? 100,
                    'status' => 1
                ]);
            }

            // 2. Link to Branch (or Warehouse)
            $query = \Illuminate\Support\Facades\DB::table('um_branch_has_resources')
                ->where('pln_resource_id', $resource->id);

            if (is_null($dbBranchId)) {
                $query->whereNull('um_branch_id');
            } else {
                $query->where('um_branch_id', $dbBranchId);
            }

            $exists = $query->exists();

            if (!$exists) {
                \Illuminate\Support\Facades\DB::table('um_branch_has_resources')->insert([
                    'um_branch_id' => $dbBranchId,
                    'pln_resource_id' => $resource->id,
                    'pln_department_id' => $request->pln_department_id,
                    'created_by' => $user->id,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Resource saved successfully!',
                'resource' => $resource
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to save resource: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:pln_production_schedules,id',
            'start_time' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            $schedule = PlnProductionSchedule::findOrFail($request->schedule_id);

            $startTime = Carbon::parse($request->start_time);
            $endTime = $startTime->copy()->addMinutes((int) $request->duration_minutes);

            $schedule->update([
                'pln_resource_id' => $request->input('resource_id', $schedule->pln_resource_id),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'notes' => $request->notes,
                'quantity' => $request->input('quantity', $schedule->quantity),
                'user_id' => $request->input('user_id'),
                'status' => $request->input('status', $schedule->status), // Maintain old status if not provided
                'um_branch_id' => session('current_branch_id'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully!',
                'schedule' => $schedule
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:pln_production_schedules,id',
        ]);

        try {
            $schedule = PlnProductionSchedule::findOrFail($request->schedule_id);
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
        ]);

        try {
            $department = PlnDepartment::create([
                'name' => $request->name,
                'color' => $request->color ?? 'blue',
                'icon' => $request->icon ?? 'box',
                'status' => 1
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully!',
                'department' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create department: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getOrderRecipeDetails($id)
    {
        try {
            $order = StmOrderRequest::with([
                'orderProducts.productItem.recipe.ingredients.productItem.variationValue',
                'orderProducts.productItem.recipe.ingredients'
            ])->findOrFail($id);

            $branchId = auth()->user()->current_branch_id;

            $details = $order->orderProducts->map(function ($op) use ($branchId) {
                $productItem = $op->productItem;
                $recipe = $productItem ? $productItem->recipe : null;

                $ingredients = [];
                if ($recipe) {
                    $ingredients = $recipe->ingredients->map(function ($ing) use ($op, $branchId) {
                        $requiredPerUnit = $ing->quantity;
                        $totalRequired = $requiredPerUnit * $op->quantity;

                        // Fetch available stock in current branch
                        $stockQuantity = 0;
                        if ($ing->product_item_id) {
                            $stockQuantity = StmBranchStock::where('pm_product_item_id', $ing->product_item_id)
                                ->where('um_branch_id', $branchId)
                                ->sum('quantity');
                        }

                        // Calculate Total Stock (Quantity * Variation Value) and Get Unit
                        $calculatedStock = $stockQuantity;
                        $stockUnit = '';

                        if ($ing->productItem && $ing->productItem->variationValue) {
                            $val = $ing->productItem->variationValue;
                            $multiplier = is_numeric($val->variation_value) ? (float) $val->variation_value : 1;
                            $unitId = $val->unit_of_measurement_id;

                            $calculatedStock = $stockQuantity * $multiplier;
                            $stockUnit = CommonVariables::$UnitOfMeasurement[$unitId] ?? '';
                        }

                        // Normalize Units for Comparison
                        $normalizedStock = $calculatedStock;
                        $normalizedRequired = $totalRequired;

                        $rUnit = strtolower($ing->unit);
                        $sUnit = strtolower($stockUnit);

                        if ($sUnit !== $rUnit && $sUnit && $rUnit) {
                            if ($sUnit === 'kg' && $rUnit === 'g') {
                                $normalizedStock *= 1000;
                            } elseif ($sUnit === 'g' && $rUnit === 'kg') {
                                $normalizedStock /= 1000;
                            } elseif ($sUnit === 'l' && $rUnit === 'ml') {
                                $normalizedStock *= 1000;
                            } elseif ($sUnit === 'ml' && $rUnit === 'l') {
                                $normalizedStock /= 1000;
                            }
                        }

                        return [
                            'name' => $ing->ingredient_name,
                            'unit' => $ing->unit,
                            'required_per_unit' => $requiredPerUnit,
                            'total_required' => $totalRequired,
                            'available_stock' => $calculatedStock,
                            'stock_unit' => $stockUnit,
                            'is_sufficient' => $normalizedStock >= $normalizedRequired
                        ];
                    });
                }

                return [
                    'product_name' => $productItem ? $productItem->product_name : 'Unknown Product',
                    'order_quantity' => $op->quantity,
                    'recipe_name' => $recipe ? $recipe->name : 'No Recipe Found',
                    'ingredients' => $ingredients
                ];
            });

            return response()->json([
                'success' => true,
                'order_number' => $order->order_number,
                'customer' => $order->customer ? $order->customer->name : 'Unknown',
                'products' => $details
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch details: ' . $e->getMessage()
            ], 500);
        }
    }
}
