<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\RecipeInstruction;
use App\Models\PlnProductionSchedule;
use App\Models\StmOrderRequest;
use App\Models\PlnResource;
use App\Models\UmUser;
use App\Models\UmBranch;
use App\CommonVariables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionManagementController extends Controller
{
    public function productionManageIndex()
    {
        return view('productionManagement.overview');
    }

    public function recipeManageIndex()
    {
        // Fetch all recipes with their ingredients and instructions
        $recipes = Recipe::with(['ingredients', 'instructions'])->get();

        // Calculate statistics
        $stats = [
            ['label' => 'Total Recipes', 'value' => $recipes->count(), 'icon' => 'book-open', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50'],
            ['label' => 'Active Recipes', 'value' => $recipes->where('status', 2)->count(), 'icon' => 'check-circle', 'color' => 'text-green-600', 'bg' => 'bg-green-50'],
            ['label' => 'Inactive Recipes', 'value' => $recipes->where('status', 1)->count(), 'icon' => 'edit', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50'],
            ['label' => 'Average Cost', 'value' => 'Rs. ' . number_format($recipes->avg('cost'), 2), 'icon' => 'calculator', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
        ];

        // Get recent activity (last 5 recipes created/updated)
        $recentActivity = [];
        $recentRecipes = Recipe::orderBy('updated_at', 'desc')->limit(5)->get();
        foreach ($recentRecipes as $recipe) {
            $action = $recipe->created_at->eq($recipe->updated_at) ? 'New recipe created' : 'Recipe updated';
            $recentActivity[] = [
                'action' => $action,
                'target' => $recipe->name . ' ' . $recipe->version,
                'time' => $recipe->updated_at->diffForHumans(),
                'icon' => $action === 'New recipe created' ? 'plus' : 'edit',
                'color' => $action === 'New recipe created' ? 'text-green-600 bg-green-50' : 'text-blue-600 bg-blue-50'
            ];
        }

        // Get featured recipes (most used, based on some criteria - for now using cost as placeholder)
        $featuredRecipes = $recipes->sortByDesc('cost')->take(4)->map(function ($recipe) {
            // Add usage and trend data to the full recipe object
            $recipeArray = $recipe->toArray();
            $recipeArray['usage'] = rand(10, 50); // Placeholder for actual usage data
            $recipeArray['trend'] = rand(0, 1) ? 'up' : 'down'; // Random trend for demo
            return $recipeArray;
        })->toArray();

        // Calculate waste processing statistics
        // Get all recipe IDs that have byproducts (these are waste processing recipes)
        $recipeIdsWithByproducts = \App\Models\RecipeByproduct::distinct()->pluck('recipe_id');
        $wasteRecipes = Recipe::whereIn('id', $recipeIdsWithByproducts)->get();
        $activeWasteRecipes = $wasteRecipes->where('status', 2); // 2 = active
        $totalByproducts = \App\Models\RecipeByproduct::whereIn('recipe_id', $recipeIdsWithByproducts)->count();
        $avgWasteCost = $activeWasteRecipes->count() > 0 ? $activeWasteRecipes->avg('cost') : 0;

        $wasteStats = [
            'total_recipes' => $wasteRecipes->count(), // Total waste recipes (all statuses)
            'active_recipes' => $activeWasteRecipes->count(), // Active waste recipes
            'total_byproducts' => $totalByproducts,
            'avg_cost' => $avgWasteCost
        ];

        return view('productionManagement.recipeManagement', compact('recipes', 'stats', 'recentActivity', 'featuredRecipes', 'wasteStats'));
    }

    public function productionSchedulingIndex()
    {
        // 1. Fetch Production Tasks (Last 30 days to Future)
        $schedules = PlnProductionSchedule::with(['resource', 'user'])
            ->where('start_time', '>=', now()->subDays(30))
            ->get();

        $tasks = $schedules->map(function ($sch) {
            $start = Carbon::parse($sch->start_time);
            $end = Carbon::parse($sch->end_time);

            // Map status code into string/class if needed, or pass raw
            // 1=Scheduled, 2=In Progress, 3=Completed, 4=Delayed
            $statusLabel = 'scheduled';
            $bgClass = 'bg-blue-100 text-blue-700 border-blue-200';
            $dotClass = 'bg-blue-500';

            switch ($sch->status) {
                case CommonVariables::$productionScheduled:
                    $statusLabel = 'scheduled';
                    $bgClass = 'bg-blue-100 text-blue-700 border-blue-200';
                    $dotClass = 'bg-blue-500';
                    break;
                case CommonVariables::$productionInProgress:
                    $statusLabel = 'in-progress';
                    $bgClass = 'bg-amber-100 text-amber-700 border-amber-200';
                    $dotClass = 'bg-amber-500';
                    break;
                case CommonVariables::$productionCompleted:
                    $statusLabel = 'completed';
                    $bgClass = 'bg-green-100 text-green-700 border-green-200';
                    $dotClass = 'bg-green-500';
                    break;
                case CommonVariables::$productionDelayed:
                    $statusLabel = 'delayed';
                    $bgClass = 'bg-red-100 text-red-700 border-red-200';
                    $dotClass = 'bg-red-500';
                    break;
            }

            return [
                'id' => $sch->id,
                'date' => $start->toDateString(),
                'recipe' => $sch->notes ?? 'Production', // Using notes as recipe name/title for now
                'batch' => $sch->quantity . ' units',
                'start' => $start->format('H:i'),
                'end' => $end->format('H:i'),
                'duration' => $start->diffInMinutes($end),
                'status' => $statusLabel,
                'statusCode' => $sch->status,
                'user' => $sch->user ? "{$sch->user->first_name} {$sch->user->last_name}" : 'Unassigned',
                'oven' => $sch->pln_resource_id, // Resource ID
                'resourceName' => $sch->resource->name ?? 'Unknown',
                'priority' => 'medium', // Default
                'bg' => $bgClass,
                'dot' => $dotClass,
                'notes' => $sch->notes
            ];
        });

        // 2. Fetch Pending Orders
        $pendingOrdersRaw = StmOrderRequest::with(['customer', 'orderProducts.productItem'])
            ->where('status', '!=', 'completed')
            ->orderBy('delivery_date', 'asc')
            ->get();

        $pendingOrders = $pendingOrdersRaw->map(function ($order) {
            return [
                'id' => $order->id,
                'number' => $order->order_number,
                'customer' => $order->customer ? $order->customer->name : 'Walk-in',
                'priority' => 'medium', // Default
                'priority_color' => 'bg-amber-500',
                'items' => $order->orderProducts->map(function ($op) {
                    return [
                        'name' => $op->productItem->product_name ?? 'Unknown',
                        'qty' => $op->quantity . ' pcs' // Assumption
                    ];
                }),
                'date' => $order->delivery_date ? 'Due: ' . $order->delivery_date : 'No deadline'
            ];
        });

        // 3. Fetch Resources for Dropdown/Grid
        $resources = PlnResource::where('status', 1)->get()->map(function ($res) {
            return ['id' => $res->id, 'name' => $res->name, 'type' => $res->type];
        });

        // 4. Fetch Recipes for Dropdown
        $recipes = Recipe::where('status', 2)->get(['id', 'name']);

        return view('productionManagement.productionScheduling', compact('tasks', 'pendingOrders', 'resources', 'recipes'));
    }

    public function kitchenProductionIndex()
    {
        return view('productionManagement.kitchenProduction');
    }

    public function productionExecutionIndex()
    {
        $branchId = session('branch_id');

        $departments = [];
        if ($branchId) {
            $branch = UmBranch::with('departments')->find($branchId);
            if ($branch && $branch->departments) {
                $departments = $branch->departments->pluck('name')->toArray();
            }
        }

        $query = PlnProductionSchedule::with([
            'productItem.recipe.ingredients',
            'productItem.recipe.instructions',
            'resource.department',
            'user',
            'scheduleInstructions' // Eager load the tracking data
        ]);

        if ($branchId) {
            $query->where('um_branch_id', $branchId);
        }

        $schedules = $query->orderBy('start_time', 'asc')->get();

        $batches = $schedules->map(function ($sch) {
            $recipe = $sch->productItem->recipe ?? null;
            $start = $sch->start_time ? Carbon::parse($sch->start_time) : now();

            $status = 'pending';
            if ($sch->status == CommonVariables::$productionInProgress) {
                $status = 'in-progress';
            } elseif ($sch->status == CommonVariables::$productionCompleted) {
                $status = 'completed';
            }

            $ingredients = [];
            if ($recipe && $recipe->ingredients) {
                $ingredients = $recipe->ingredients->map(function ($ing) {
                    return [
                        'name' => $ing->name,
                        'needed' => $ing->quantity,
                        'unit' => $ing->unit,
                        'available' => 999,
                        'status' => 'ok'
                    ];
                });
            }

            $steps = [];
            if ($recipe && $recipe->instructions) {
                // Map instructions, merging with tracking data if available
                $steps = $recipe->instructions->sortBy('step_number')->values()->map(function ($inst) use ($sch) {
                    $tracking = $sch->scheduleInstructions->where('instruction_id', $inst->id)->first();

                    $isCompleted = false;
                    $isInProgress = false;
                    $startTime = null;
                    $endTime = null;

                    if ($tracking) {
                        if ($tracking->status == CommonVariables::$instructionCompleted) {
                            $isCompleted = true;
                            $endTime = $tracking->end_time ? Carbon::parse($tracking->end_time)->format('h:i A') : null;
                            $startTime = $tracking->start_time ? Carbon::parse($tracking->start_time)->format('h:i A') : null;
                        } elseif ($tracking->status == CommonVariables::$instructionInProgress) {
                            $isInProgress = true;
                            $startTime = $tracking->start_time ? Carbon::parse($tracking->start_time)->format('h:i A') : null;
                        }
                    }

                    return [
                        'id' => $inst->id,
                        'name' => $inst->step_description,
                        'duration' => 15,
                        'type' => 'prep',
                        'completed' => $isCompleted,
                        'inProgress' => $isInProgress,
                        'startTime' => $startTime,
                        'endTime' => $endTime
                    ];
                });
            }

            // Calculate current step for progress bar
            $currentStepIdx = 0;
            if (count($steps) > 0) {
                foreach ($steps as $idx => $step) {
                    if ($step['completed']) {
                        $currentStepIdx = $idx + 1;
                    } elseif ($step['inProgress']) {
                        $currentStepIdx = $idx;
                        break;
                    }
                }
            }
            if ($status == 'completed')
                $currentStepIdx = count($steps);


            $prodName = $sch->productItem ? $sch->productItem->product_name : 'Unknown Product';
            $section = $sch->resource && $sch->resource->department ? $sch->resource->department->name : 'Kitchen';

            return [
                'id' => $sch->id,
                'recipeName' => $recipe ? $recipe->name : $prodName,
                'recipeIcon' => '👨‍🍳',
                'section' => $section,
                'scheduledTime' => $start->format('h:i A'),
                'quantity' => $sch->quantity,
                'unit' => 'units',
                'status' => $status,
                'priority' => 'medium',
                'assignedTo' => $sch->resource ? $sch->resource->name : ($sch->user ? $sch->user->first_name : 'Unassigned'),
                'currentStep' => $currentStepIdx,
                'totalSteps' => count($steps),
                'ingredients' => $ingredients,
                'steps' => $steps,
                'isWasteProcessing' => $recipe ? ($recipe->is_waste == 1) : false,
            ];
        });

        return view('productionManagement.productionExecution', compact('batches', 'departments'));
    }

    public function wastageTrackingIndex()
    {
        return view('productionManagement.wastageTracking');
    }

    public function batchTrackingIndex()
    {
        return view('productionManagement.batchTracking');
    }

    public function storeRecipe(Request $request)
    {
        // dd($request->all());
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string',
                'categories' => 'nullable|string',
                'yield' => 'nullable|string',
                'prep_time' => 'nullable|string',
                'cost' => 'required|numeric',
                'status' => 'required|integer|in:1,2', // 1 = inactive, 2 = active
                //  'version' => 'required|string',
                'is_waste' => 'required|string',
                'shelf_life' => 'nullable|integer',
                'shelf_life_unit' => 'nullable|string',
                'product_item_id' => 'nullable|exists:pm_product_item,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
                'ingredients' => 'nullable|array',
                'ingredients.*.product_item_id' => 'nullable|exists:pm_product_item,id',
                'ingredients.*.name' => 'nullable|string',
                'ingredients.*.quantity' => 'nullable|numeric',
                'ingredients.*.unit' => 'nullable|string',
                // 'ingredients.*.cost_per_unit' => 'nullable|numeric', // Removed
                'ingredients.*.is_aged' => 'nullable|boolean', // Added
                'ingredients.*.aged_days' => 'nullable|integer', // Added
                'ingredients.*.type' => 'nullable|string',
                'instructions' => 'nullable|array',
                'instructions.*.step_description' => 'nullable|string',
                'instructions.*.step_number' => 'nullable|integer',
            ]);

            // Handle image upload
            $imagePaths = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('recipes', $imageName, 'public');
                $imagePaths = [$imagePath];
            }

            $isWaste = 0;

            if ($request->is_waste == "true") {

                $isWaste = 1;

            }

            $recipe = Recipe::create([
                'name' => $request->name,
                'description' => $request->description,
                'category' => $request->category,
                //  'categories' => $request->categories,
                'yield' => $request->yield,
                'prep_time' => $request->prep_time,
                'cost' => $request->cost,
                'status' => (int) $request->status, // Cast to integer
                'version' => "$request->version",
                'is_waste' => $isWaste,
                'shelf_life' => $request->shelf_life,
                'shelf_life_unit' => $request->shelf_life_unit,
                'product_item_id' => $request->product_item_id,
                'image_paths' => $imagePaths,
            ]);

            // Create ingredients
            if ($request->has('ingredients') && is_array($request->ingredients)) {
                foreach ($request->ingredients as $index => $ingredientData) { // Added index
                    // Skip empty ingredients
                    if (empty($ingredientData['name']) && empty($ingredientData['product_item_id'])) {
                        continue;
                    }

                    $ingredient = [
                        'recipe_id' => $recipe->id,
                        'quantity' => $ingredientData['quantity'] ?? 0,
                        'unit' => $ingredientData['unit'] ?? 'pcs',
                        // 'cost_per_unit' => $ingredientData['cost_per_unit'] ?? 0, // Removed
                        'is_aged' => isset($ingredientData['is_aged']) ? filter_var($ingredientData['is_aged'], FILTER_VALIDATE_BOOLEAN) : false,
                        'aged_days' => isset($ingredientData['aged_days']) ? (int) $ingredientData['aged_days'] : null,
                        'type' => $ingredientData['type'] ?? 'ingredient',
                        'sort_order' => $index, // Use loop index for sort order
                    ];

                    // Set either product_item_id or name based on what's provided
                    if (!empty($ingredientData['product_item_id'])) {
                        $ingredient['product_item_id'] = $ingredientData['product_item_id'];
                        // If we have a product item, we can get its name
                        $productItem = \App\Models\PmProductItem::find($ingredientData['product_item_id']);
                        $ingredient['name'] = $productItem ? $productItem->product_name : $ingredientData['name'] ?? 'Unknown Ingredient';
                    } else {
                        $ingredient['name'] = $ingredientData['name'] ?? 'Unnamed Ingredient';
                        $ingredient['product_item_id'] = null;
                    }

                    RecipeIngredient::create($ingredient);
                }
            }

            // Create instructions
            if ($request->has('instructions') && is_array($request->instructions)) {
                foreach ($request->instructions as $instructionData) {
                    // Skip empty instructions
                    if (empty($instructionData['step_description'])) {
                        continue;
                    }

                    RecipeInstruction::create([
                        'recipe_id' => $recipe->id,
                        'step_description' => $instructionData['step_description'],
                        'step_number' => $instructionData['step_number'] ?? 1,
                        'sort_order' => $instructionData['sort_order'] ?? 0,
                    ]);
                }
            }

            // Create waste recovery byproducts and NRV
            if ($request->has('wastage_recovery_by_products') && is_array($request->wastage_recovery_by_products)) {
                foreach ($request->wastage_recovery_by_products as $byproductData) {
                    // Skip if no product name is provided (basic validation)
                    if (empty($byproductData['product_name']) && empty($byproductData['product_item_id'])) {
                        continue;
                    }

                    $byproduct = \App\Models\RecipeByproduct::create([
                        'recipe_id' => $recipe->id,
                        'product_item_id' => $byproductData['product_item_id'] ?? null,
                        'product_name' => $byproductData['product_name'] ?? null,
                        'quantity' => $byproductData['quantity'] ?? 0,
                        'unit' => $byproductData['unit'] ?? 'kg',
                    ]);

                    \App\Models\RecipeByproductNrv::create([
                        'recipe_byproduct_id' => $byproduct->id,
                        'product_item_id' => $byproductData['nrv_product_item_id'] ?? null,
                        'product_name' => $byproductData['nrv_product_name'] ?? null,
                        'market_value' => $byproductData['market_value'] ?? 0,
                        'processing_cost' => $byproductData['processing_cost'] ?? 0,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Recipe created successfully',
                'recipe' => $recipe->load('ingredients', 'instructions')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the recipe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRecipe($id)
    {
        $recipe = Recipe::with(['ingredients', 'instructions'])->findOrFail($id);
        return response()->json(['recipe' => $recipe]);
    }

    public function searchProductItems(Request $request)
    {
        $query = $request->input('query');

        // Search for product items that match the query
        $products = \App\Models\PmProductItem::where('product_name', 'LIKE', '%' . $query . '%')
            ->select('id', 'product_name')
            ->limit(10)
            ->get();

        // Calculate median costing price for each product from stm_stock
        $products = $products->map(function ($product) {
            // Get all costing prices for this product from stm_stock, ordered
            $costingPrices = \App\Models\StmStock::where('pm_product_item_id', $product->id)
                ->whereNotNull('costing_price')
                ->where('costing_price', '>', 0)
                ->orderBy('costing_price', 'asc')
                ->pluck('costing_price')
                ->toArray();

            // Calculate median
            $median = 0;
            $count = count($costingPrices);

            if ($count > 0) {
                if ($count % 2 == 0) {
                    // Even number of values - average of middle two
                    $median = ($costingPrices[$count / 2 - 1] + $costingPrices[$count / 2]) / 2;
                } else {
                    // Odd number of values - middle value
                    $median = $costingPrices[floor($count / 2)];
                }
            }

            $product->median_costing_price = round($median, 2);
            return $product;
        });

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    public function startBatch(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:pln_production_schedules,id'
        ]);

        try {
            DB::beginTransaction();

            $schedule = PlnProductionSchedule::with('productItem.recipe.instructions')->findOrFail($request->schedule_id);

            if ($schedule->status == CommonVariables::$productionInProgress) {
                return response()->json(['success' => false, 'message' => 'Batch already in progress']);
            }

            // Update Status
            $schedule->status = CommonVariables::$productionInProgress;
            $schedule->save();

            // Create Schedule Instructions if recipe exists
            if ($schedule->productItem && $schedule->productItem->recipe && $schedule->productItem->recipe->instructions) {
                $instructions = $schedule->productItem->recipe->instructions->sortBy('step_number');

                $iteration = 0;
                foreach ($instructions as $inst) {
                    $status = CommonVariables::$instructionPending;
                    $startTime = null;

                    // Start the first step
                    if ($iteration === 0) {
                        $status = CommonVariables::$instructionInProgress;
                        $startTime = now();
                    }

                    \App\Models\PlnScheduleInstruction::create([
                        'production_schedule_id' => $schedule->id,
                        'instruction_id' => $inst->id,
                        'status' => $status,
                        'start_time' => $startTime
                    ]);
                    $iteration++;
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Batch started successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to start batch: ' . $e->getMessage()], 500);
        }
    }

    public function completeStep(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:pln_production_schedules,id',
            'instruction_id' => 'required|exists:pm_recipe_instructions,id'
        ]);

        try {
            DB::beginTransaction();

            // 1. Find the specific instruction record for this schedule
            $currentInstruction = \App\Models\PlnScheduleInstruction::where('production_schedule_id', $request->schedule_id)
                ->where('instruction_id', $request->instruction_id)
                ->firstOrFail();

            // 2. Mark Current as Completed
            $currentInstruction->status = CommonVariables::$instructionCompleted;
            $currentInstruction->end_time = now();
            // $currentInstruction->notes = "Completed by " . auth()->user()->first_name; // Optional: Add notes column in DB if needed or append
            $currentInstruction->save();

            // 3. Find Next Instruction
            // Get all instructions for this schedule to find the next one in sequence
            // Assuming we rely on recipe instruction step_number or ID order
            // Get all instructions for the recipe to determine order
            $schedule = PlnProductionSchedule::with('productItem.recipe.instructions')->findOrFail($request->schedule_id);
            $recipeInstructions = $schedule->productItem->recipe->instructions->sortBy('step_number')->values();

            $nextInstructionId = null;
            $foundCurrent = false;

            foreach ($recipeInstructions as $inst) {
                if ($foundCurrent) {
                    $nextInstructionId = $inst->id;
                    break;
                }
                if ($inst->id == $request->instruction_id) {
                    $foundCurrent = true;
                }
            }

            if ($nextInstructionId) {
                // 4. Start Next Instruction
                $nextInstruction = \App\Models\PlnScheduleInstruction::where('production_schedule_id', $request->schedule_id)
                    ->where('instruction_id', $nextInstructionId)
                    ->first();

                if ($nextInstruction) {
                    $nextInstruction->status = CommonVariables::$instructionInProgress;
                    $nextInstruction->start_time = now();
                    $nextInstruction->save();
                }
            } else {
                // 5. No Next Instruction
                // Logic Changed: Batch stays 'In Progress' until manual QC completion
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Step completed successfully',
                'completed_at' => now()->format('h:i A'),
                'next_started_at' => now()->format('h:i A'),
                'is_batch_completed' => false
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to complete step: ' . $e->getMessage()], 500);
        }
    }

    public function completeBatch(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:pln_production_schedules,id',
            'actual_output' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $schedule = PlnProductionSchedule::findOrFail($request->schedule_id);

            if ($schedule->status == CommonVariables::$productionCompleted) {
                return response()->json(['success' => false, 'message' => 'Batch already completed']);
            }

            // Update Status
            $schedule->status = CommonVariables::$productionCompleted;
            $schedule->end_time = now();

            // Save QC Data to new columns
            $schedule->actual_output = $request->actual_output;
            $schedule->waste_reason = $request->waste_reason;
            $schedule->quality_note = $request->notes;

            // Automatically flag as waste if output is less than target
            if ($request->actual_output < $schedule->quantity) {
                $schedule->is_waste = 1;
            } else {
                $schedule->is_waste = 0;
            }

            // Optional: We can still append to notes if needed, but for now we trust the new columns.
            // If main notes are empty and quality note exists, maybe we sync them, but data separation is better.

            $schedule->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Batch completed successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to complete batch: ' . $e->getMessage()], 500);
        }
    }
}
