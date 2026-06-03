<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StmOrderRequest;
use App\Models\CmCustomer;
use App\Models\AdAgent;
use App\Models\StmStock;
use App\Models\PlnProductionSchedule;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // ── KPI STATS ──────────────────────────────────────────────────────────
        $todayRevenue = StmOrderRequest::whereDate('created_at', today())
            ->where('status', '!=', 2)
            ->sum('grand_total');

        $yesterdayRevenue = StmOrderRequest::whereDate('created_at', today()->subDay())
            ->where('status', '!=', 2)
            ->sum('grand_total');

        $revenueGrowth = $yesterdayRevenue > 0
            ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1)
            : null;

        $todayOrdersCount     = StmOrderRequest::whereDate('created_at', today())->count();
        $yesterdayOrdersCount = StmOrderRequest::whereDate('created_at', today()->subDay())->count();
        $ordersGrowth = $yesterdayOrdersCount > 0
            ? round((($todayOrdersCount - $yesterdayOrdersCount) / $yesterdayOrdersCount) * 100, 1)
            : null;

        $totalCustomersCount = CmCustomer::count();
        $newCustomersToday   = CmCustomer::whereDate('created_at', today())->count();

        $agentCount       = AdAgent::count();
        $activeAgentCount = AdAgent::where('status', 1)->count();

        // ── PENDING APPROVALS ──────────────────────────────────────────────────
        $pendingApprovals = StmOrderRequest::where('status', 0)->count();

        // ── THIS MONTH REVENUE ─────────────────────────────────────────────────
        $monthRevenue = StmOrderRequest::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 2)
            ->sum('grand_total');

        // ── ORDER STATUS BREAKDOWN (today) ─────────────────────────────────────
        $orderStatusBreakdown = [
            'pending'    => StmOrderRequest::whereDate('created_at', today())->where('status', 0)->count(),
            'approved'   => StmOrderRequest::whereDate('created_at', today())->where('status', 1)->count(),
            'dispatched' => StmOrderRequest::whereDate('created_at', today())->where('status', 6)->count(),
            'completed'  => StmOrderRequest::whereDate('created_at', today())->where('status', 7)->count(),
        ];

        // ── WEEKLY REVENUE (last 7 days) ───────────────────────────────────────
        $weeklyRevenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date    = now()->subDays($i)->format('Y-m-d');
            $label   = now()->subDays($i)->format('D');
            $revenue = StmOrderRequest::whereDate('created_at', $date)
                ->where('status', '!=', 2)
                ->sum('grand_total');
            $weeklyRevenue[] = [
                'label'   => $label,
                'revenue' => (float) $revenue,
                'date'    => $date,
            ];
        }

        // ── MONTHLY REVENUE COMPARISON (last 6 months) ─────────────────────────
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month   = now()->subMonths($i);
            $revenue = StmOrderRequest::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', '!=', 2)
                ->sum('grand_total');
            $monthlyRevenue[] = [
                'label'   => $month->format('M y'),
                'revenue' => (float) $revenue,
            ];
        }

        // ── RECENT ORDERS ──────────────────────────────────────────────────────
        $recentOrders = StmOrderRequest::with(['customer', 'agent'])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get()
            ->map(function ($order) {
                $order->status_label = match ($order->status) {
                    0 => 'Pending',
                    1 => 'Approved',
                    2 => 'Rejected',
                    3 => 'In Production',
                    4 => 'Ready',
                    5 => 'Delivery',
                    6 => 'Dispatched',
                    7 => 'Completed',
                    default => 'Unknown'
                };
                $order->status_color = match ($order->status) {
                    0 => 'bg-amber-100 text-amber-700 border-amber-200',
                    1 => 'bg-blue-100 text-blue-700 border-blue-200',
                    2 => 'bg-red-100 text-red-700 border-red-200',
                    3 => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                    4 => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    5 => 'bg-cyan-100 text-cyan-700 border-cyan-200',
                    6 => 'bg-purple-100 text-purple-700 border-purple-200',
                    7 => 'bg-green-100 text-green-700 border-green-200',
                    default => 'bg-gray-100 text-gray-700 border-gray-200'
                };
                $order->status_dot = match ($order->status) {
                    0 => '#f59e0b',
                    1 => '#3b82f6',
                    2 => '#ef4444',
                    3 => '#6366f1',
                    4 => '#10b981',
                    5 => '#06b6d4',
                    6 => '#a855f7',
                    7 => '#22c55e',
                    default => '#6b7280'
                };
                return $order;
            });

        // ── LOW STOCK ITEMS ────────────────────────────────────────────────────
        $lowStockItems = StmStock::with('productItem')
            ->where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->take(6)
            ->get();

        // ── TODAY'S PRODUCTION SCHEDULES ───────────────────────────────────────
        $todayProduction = PlnProductionSchedule::with(['productItem', 'resource'])
            ->whereDate('start_time', today())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();

        // ── TOP AGENTS THIS MONTH ──────────────────────────────────────────────
        $topAgents = DB::table('stm_order_requests')
            ->join('ad_agent', 'stm_order_requests.agent_id', '=', 'ad_agent.id')
            ->selectRaw('ad_agent.agent_name, ad_agent.agent_code, SUM(stm_order_requests.grand_total) as total_sales, COUNT(stm_order_requests.id) as order_count')
            ->whereMonth('stm_order_requests.created_at', now()->month)
            ->whereYear('stm_order_requests.created_at', now()->year)
            ->where('stm_order_requests.status', '!=', 2)
            ->whereNotNull('stm_order_requests.agent_id')
            ->groupBy('ad_agent.id', 'ad_agent.agent_name', 'ad_agent.agent_code')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        return view('dashboard.adminDashboard', compact(
            'todayRevenue',
            'yesterdayRevenue',
            'revenueGrowth',
            'todayOrdersCount',
            'yesterdayOrdersCount',
            'ordersGrowth',
            'totalCustomersCount',
            'newCustomersToday',
            'agentCount',
            'activeAgentCount',
            'pendingApprovals',
            'monthRevenue',
            'orderStatusBreakdown',
            'weeklyRevenue',
            'monthlyRevenue',
            'recentOrders',
            'lowStockItems',
            'todayProduction',
            'topAgents'
        ));
    }
}
