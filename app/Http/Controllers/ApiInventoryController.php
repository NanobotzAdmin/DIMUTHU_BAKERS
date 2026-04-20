<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\AdDailyLoad;
use App\Models\AdDailyLoadItem;
use App\Models\SmSuperviser;
use App\Models\StmBranchStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiInventoryController extends Controller
{

    // private function getAgentId()
    // {
    //     $user = auth()->user();
    //     if ($user && $user->user_role_id == 8) {
    //         $agent = AdAgent::where('user_id', $user->id)->first();
    //         return $agent ? $agent->id : null;
    //     }
    //     return null;
    // }

    // private function getSupervisorId()
    // {
    //     $user = auth()->user();
    //     if ($user && $user->user_role_id == 10) {
    //         $supervisor = SmSuperviser::where('user_id', $user->id)->first();
    //         return $supervisor ? $supervisor->id : null;
    //     }
    //     return null;
    // }
    /**
     * Get agent's branch stock inventory
     */
    public function getAgentStock()
    {
        $agentId = $this->getAgentId();
        if (!$agentId) {
            return response()->json(['status' => false, 'message' => 'Agent not found'], 403);
        }

        try {
            $stocks = StmBranchStock::where('agent_id', $agentId)
                ->where('status', 1)
                ->with([
                    'productItem' => function ($q) {
                        $q->select('id', 'product_name', 'selling_price', 'wholesale_percentage', 'pm_product_id')
                            ->with([
                                'product' => function ($q2) {
                                    $q2->select('id', 'product_name');
                                }
                            ]);
                    }
                ])
                ->get()
                ->groupBy('pm_product_item_id')
                ->map(function ($group) {
                    $first = $group->first();
                    $productItem = $first->productItem;
                    $category = $productItem && $productItem->product
                        ? ($productItem->product->product_name ?? 'Uncategorized')
                        : 'Uncategorized';

                    return [
                        'id' => $first->pm_product_item_id,
                        'product_name' => $productItem ? $productItem->product_name : 'Unknown',
                        'category' => $category,
                        'quantity' => $group->sum('quantity'),
                        'selling_price' => $productItem ? (float) $productItem->selling_price : 0,
                        'wholesale_price' => $productItem ? (float) $productItem->wholesale_price : 0,
                    ];
                })
                ->values();

            return response()->json([
                'status' => true,
                'data' => $stocks,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Agent Stock Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to load inventory: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get supervisor's active daily loads stock inventory
     */
    public function getSupervisorStock()
    {
        $supervisorId = $this->getSupervisorId();
        if (!$supervisorId) {
            return response()->json(['status' => false, 'message' => 'Supervisor not found'], 403);
        }

        try {
            // Get active daily loads for the supervisor
            $activeLoads = AdDailyLoad::where('supervisor_id', $supervisorId)
                ->where('status', 1)
                ->where('load_status', 3)
                ->where('is_mark_as_loaded', 1)
                // ->where('load_date', date('Y-m-d'))
                ->pluck('id');

            // Get stock from items belonging to these active loads
            $stockItems = AdDailyLoadItem::whereIn('daily_load_id', $activeLoads)
                ->with([
                    'product' => function ($q) {
                        $q->select('id', 'product_name', 'selling_price', 'wholesale_percentage', 'pm_product_id')
                            ->with([
                                'product' => function ($q2) {
                                    $q2->select('id', 'product_name');
                                }
                            ]);
                    }
                ])
                ->get()
                ->groupBy('product_item_id')
                ->map(function ($group) {
                    $first = $group->first();
                    $productItem = $first->product;
                    $category = $productItem && $productItem->product
                        ? ($productItem->product->product_name ?? 'Uncategorized')
                        : 'Uncategorized';

                    return [
                        'id' => $first->product_item_id,
                        'product_name' => $productItem ? $productItem->product_name : 'Unknown',
                        'category' => $category,
                        // Sum available_quantity
                        'quantity' => $group->sum('available_quantity'),
                        'selling_price' => $productItem ? (float) $productItem->selling_price : 0,
                        'wholesale_price' => $productItem ? (float) $productItem->wholesale_price : 0,
                    ];
                })
                ->values();

            return response()->json([
                'status' => true,
                'data' => $stockItems,
            ]);
        } catch (\Exception $e) {
            Log::error('Get Supervisor Stock Failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to load supervisor inventory: ' . $e->getMessage(),
            ], 500);
        }
    }
}
