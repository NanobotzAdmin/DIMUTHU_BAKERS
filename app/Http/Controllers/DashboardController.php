<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        $today = now()->startOfDay();
        
        // Stats
        $todayRevenue = \App\Models\StmOrderRequest::whereDate('created_at', today())
            ->where('status', '!=', 2) // Not rejected
            ->sum('grand_total');
            
        $todayOrdersCount = \App\Models\StmOrderRequest::whereDate('created_at', today())->count();
        $totalCustomersCount = \App\Models\CmCustomer::count();
        $agentCount = \App\Models\AdAgent::count();
        
        // Recent Orders
        $recentOrders = \App\Models\StmOrderRequest::with(['customer', 'agent'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($order) {
                $order->status_label = match ($order->status) {
                    0 => 'Pending Approval',
                    1 => 'Approved',
                    2 => 'Rejected',
                    3 => 'In Production',
                    4 => 'Ready for Pickup',
                    5 => 'Out for Delivery',
                    6 => 'Dispatch Confirmed',
                    7 => 'Completed',
                    default => 'Pending'
                };
                $order->status_color = match ($order->status) {
                    0 => 'bg-amber-100 text-amber-700',
                    1 => 'bg-blue-100 text-blue-700',
                    2 => 'bg-red-100 text-red-700',
                    3 => 'bg-indigo-100 text-indigo-700',
                    4 => 'bg-emerald-100 text-emerald-700',
                    5 => 'bg-cyan-100 text-cyan-700',
                    6 => 'bg-purple-100 text-purple-700',
                    7 => 'bg-emerald-100 text-emerald-700',
                    default => 'bg-gray-100 text-gray-700'
                };
                return $order;
            });
            
        // Low Stock Items
        $lowStockItems = \App\Models\StmStock::with('productItem')
            ->where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();
            
        return view('dashboard.adminDashboard', compact(
            'todayRevenue', 
            'todayOrdersCount', 
            'totalCustomersCount', 
            'agentCount',
            'recentOrders',
            'lowStockItems'
        ));
    }

    
}
