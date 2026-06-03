@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    /* ── Dashboard Custom Styles ───────────────────────────────────────────── */
    .db-gradient-emerald { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
    .db-gradient-blue    { background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); }
    .db-gradient-purple  { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); }
    .db-gradient-amber   { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); }
    .db-gradient-rose    { background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%); }
    .db-gradient-cyan    { background: linear-gradient(135deg, #0e7490 0%, #06b6d4 100%); }

    .kpi-card {
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 20px rgba(0,0,0,.04);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: box-shadow .25s, transform .25s;
    }
    .kpi-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,.1);
        transform: translateY(-2px);
    }
    .kpi-icon-wrap {
        width: 3rem; height: 3rem;
        border-radius: .875rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .kpi-icon-wrap svg { width: 1.4rem; height: 1.4rem; color: #fff; }

    .panel {
        background: #fff;
        border-radius: 1.25rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 20px rgba(0,0,0,.04);
        overflow: hidden;
    }
    .panel-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f8fafc;
        display: flex; align-items: center; justify-content: space-between;
    }
    .panel-title { font-size: 1rem; font-weight: 700; color: #0f172a; margin: 0; }
    .panel-sub   { font-size: .75rem; color: #94a3b8; margin: .2rem 0 0; }

    /* Bar chart */
    .bar-chart-wrap { display: flex; align-items: flex-end; gap: .35rem; height: 80px; padding: 0 .5rem; }
    .bar-col { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .bar-bar { width: 100%; border-radius: .35rem .35rem 0 0; transition: opacity .2s; min-height: 4px; }
    .bar-bar:hover { opacity: .8; }
    .bar-label { font-size: .65rem; color: #94a3b8; font-weight: 600; }

    /* Status pill */
    .status-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .2rem .65rem; border-radius: 9999px;
        font-size: .7rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
        border: 1px solid transparent;
    }
    .status-dot { width: .45rem; height: .45rem; border-radius: 50%; display: inline-block; }

    /* Progress bar */
    .prog-track { height: 6px; border-radius: 9999px; background: #f1f5f9; overflow: hidden; }
    .prog-bar   { height: 100%; border-radius: 9999px; transition: width 1s ease; }

    /* Pulse animation */
    @keyframes pulseRing {
        0%   { transform: scale(.95); box-shadow: 0 0 0 0 rgba(16,185,129,.4); }
        70%  { transform: scale(1);   box-shadow: 0 0 0 8px rgba(16,185,129,0); }
        100% { transform: scale(.95); box-shadow: 0 0 0 0 rgba(16,185,129,0); }
    }
    .live-dot { animation: pulseRing 2s infinite; }

    /* Scrollable table */
    .db-table { width: 100%; text-align: left; border-collapse: collapse; }
    .db-table thead tr { background: #f8fafc; }
    .db-table th { padding: .75rem 1rem; font-size: .7rem; font-weight: 700; text-transform: uppercase;
                   letter-spacing: .06em; color: #64748b; white-space: nowrap; }
    .db-table td { padding: .8rem 1rem; font-size: .8rem; border-top: 1px solid #f8fafc; color: #334155; }
    .db-table tbody tr:hover td { background: #f8fafc; }

    /* Segment blocks (order pipeline) */
    .pipeline-item {
        display: flex; align-items: center; gap: .75rem;
        padding: .75rem 1rem; border-radius: .75rem;
        background: #f8fafc; border: 1px solid #f1f5f9;
        transition: background .15s;
    }
    .pipeline-item:hover { background: #f1f5f9; }
    .pipeline-icon { width: 2.25rem; height: 2.25rem; border-radius: .5rem;
                     display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .pipeline-count { font-size: 1.25rem; font-weight: 800; color: #0f172a; line-height: 1; }
    .pipeline-lbl   { font-size: .72rem; color: #64748b; font-weight: 600; }

    /* Agent rank card */
    .agent-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .6rem 0; border-bottom: 1px solid #f8fafc;
    }
    .agent-row:last-child { border-bottom: none; }
    .agent-avatar {
        width: 2.25rem; height: 2.25rem; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 800; color: #fff; flex-shrink: 0;
    }

    /* Mini chart line (sparkline-ish using SVG) */
    .sparkline { width: 60px; height: 24px; }

    /* Welcome banner */
    .welcome-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #2d3e56 100%);
        border-radius: 1.25rem;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute; top: -40px; right: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(79,70,229,.15);
    }
    .welcome-banner::after {
        content: '';
        position: absolute; bottom: -50px; right: 100px;
        width: 120px; height: 120px; border-radius: 50%;
        background: rgba(16,185,129,.1);
    }

    /* Alert badge */
    .alert-badge {
        display: inline-flex; align-items: center; gap: .3rem;
        padding: .25rem .7rem; border-radius: 9999px; font-size: .7rem; font-weight: 700;
    }
</style>

<div class="flex flex-col gap-6 w-full">

    {{-- ── WELCOME BANNER ──────────────────────────────────────────────────── --}}
    <div class="welcome-banner">
        <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-widest mb-1">
                    {{ now()->format('l, F j, Y') }}
                </p>
                <h2 class="text-white text-2xl font-bold m-0 leading-tight">
                    Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
                    {{ Auth::user()->first_name ?? 'Admin' }}! 👋
                </h2>
                <p class="text-slate-400 text-sm mt-1 m-0">
                    Here's what's happening at Dimuthu Bake house today.
                </p>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                @if($pendingApprovals > 0)
                    <a href="{{ route('order-management.index') }}"
                       class="alert-badge bg-amber-400/20 text-amber-300 border border-amber-400/30 hover:bg-amber-400/30 transition-colors no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        {{ $pendingApprovals }} Pending Approval{{ $pendingApprovals > 1 ? 's' : '' }}
                    </a>
                @endif
                <span class="alert-badge bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 live-dot inline-block"></span>
                    System Online
                </span>
                <a href="{{ route('order-management.index') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-lg transition-colors no-underline border border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    View Orders
                </a>
            </div>
        </div>
    </div>

    {{-- ── KPI CARDS ────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Today's Revenue --}}
        <div class="kpi-card">
            <div class="flex items-start justify-between mb-4">
                <div class="kpi-icon-wrap db-gradient-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                @if($revenueGrowth !== null)
                    <span class="text-xs font-bold px-2 py-1 rounded-full {{ $revenueGrowth >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                        {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs($revenueGrowth) }}%
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider m-0">Today's Revenue</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1 mb-2">Rs {{ number_format($todayRevenue, 2) }}</h3>
            <p class="text-xs text-slate-400 m-0">
                vs Rs {{ number_format($yesterdayRevenue, 2) }} yesterday
            </p>
        </div>

        {{-- Today's Orders --}}
        <div class="kpi-card">
            <div class="flex items-start justify-between mb-4">
                <div class="kpi-icon-wrap db-gradient-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                </div>
                @if($ordersGrowth !== null)
                    <span class="text-xs font-bold px-2 py-1 rounded-full {{ $ordersGrowth >= 0 ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }}">
                        {{ $ordersGrowth >= 0 ? '↑' : '↓' }} {{ abs($ordersGrowth) }}%
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider m-0">Today's Orders</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1 mb-2">{{ $todayOrdersCount }}</h3>
            <p class="text-xs text-slate-400 m-0">
                {{ $yesterdayOrdersCount }} orders yesterday
            </p>
        </div>

        {{-- Total Customers --}}
        <div class="kpi-card">
            <div class="flex items-start justify-between mb-4">
                <div class="kpi-icon-wrap db-gradient-purple">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-3-3.87"/><path d="M9 21v-2a4 4 0 0 1 3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/><path d="M8 3.13a4 4 0 0 0 0 7.75"/></svg>
                </div>
                @if($newCustomersToday > 0)
                    <span class="text-xs font-bold px-2 py-1 rounded-full bg-purple-50 text-purple-600">
                        +{{ $newCustomersToday }} today
                    </span>
                @endif
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider m-0">Total Customers</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1 mb-2">{{ number_format($totalCustomersCount) }}</h3>
            <p class="text-xs text-slate-400 m-0">Registered customer base</p>
        </div>

        {{-- Active Agents --}}
        <div class="kpi-card">
            <div class="flex items-start justify-between mb-4">
                <div class="kpi-icon-wrap db-gradient-amber">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded-full bg-amber-50 text-amber-600">
                    {{ $activeAgentCount }} active
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider m-0">Total Agents</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1 mb-2">{{ $agentCount }}</h3>
            <p class="text-xs text-slate-400 m-0">{{ $agentCount - $activeAgentCount }} inactive agents</p>
        </div>

    </div>

    {{-- ── MONTHLY REVENUE + ORDER PIPELINE ───────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Month Revenue Card --}}
        <div class="kpi-card lg:col-span-1 flex flex-col justify-between" style="background: linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%); border-color: transparent;">
            <div>
                <p class="text-indigo-200 text-xs font-semibold uppercase tracking-widest m-0">{{ now()->format('F Y') }} Revenue</p>
                <h3 class="text-white text-3xl font-black mt-2 mb-1">Rs {{ number_format($monthRevenue, 0) }}</h3>
                <p class="text-indigo-200 text-xs m-0">Total monthly sales (excl. rejected)</p>
            </div>
            {{-- Mini bar chart --}}
            <div class="mt-4">
                <div class="bar-chart-wrap" id="weeklyBarChart">
                    @php $maxRev = collect($weeklyRevenue)->max('revenue') ?: 1; @endphp
                    @foreach($weeklyRevenue as $day)
                        <div class="bar-col">
                            <div class="bar-bar"
                                 style="height: {{ max(4, round(($day['revenue'] / $maxRev) * 70)) }}px; background: rgba(255,255,255,{{ $day['date'] === now()->format('Y-m-d') ? '1' : '0.35' }});"
                                 title="{{ $day['label'] }}: Rs {{ number_format($day['revenue'],2) }}">
                            </div>
                            <span class="bar-label" style="color: rgba(199,210,254,.8);">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
                <p class="text-indigo-200 text-xs mt-2 m-0">Last 7 days revenue trend</p>
            </div>
        </div>

        {{-- Today's Order Pipeline --}}
        <div class="panel lg:col-span-2">
            <div class="panel-header">
                <div>
                    <p class="panel-title">📋 Today's Order Pipeline</p>
                    <p class="panel-sub">Live order status breakdown for {{ now()->format('M d, Y') }}</p>
                </div>
                <a href="{{ route('order-management.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors no-underline">
                    Manage →
                </a>
            </div>
            <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-3">
                @php
                    $pipeline = [
                        ['label' => 'Pending Order', 'count' => $orderStatusBreakdown['pending'],    'icon_bg' => 'background:#fef3c7', 'color' => '#d97706', 'icon' => '<path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="10"/>'],
                        ['label' => 'Approved',         'count' => $orderStatusBreakdown['approved'],   'icon_bg' => 'background:#dbeafe', 'color' => '#2563eb', 'icon' => '<polyline points="20 6 9 17 4 12"/>'],
                        ['label' => 'Dispatched',       'count' => $orderStatusBreakdown['dispatched'], 'icon_bg' => 'background:#f3e8ff', 'color' => '#9333ea', 'icon' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>'],
                        ['label' => 'Completed',        'count' => $orderStatusBreakdown['completed'],  'icon_bg' => 'background:#dcfce7', 'color' => '#16a34a', 'icon' => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'],
                    ];
                @endphp
                @foreach($pipeline as $item)
                    <div class="pipeline-item">
                        <div class="pipeline-icon" style="{{ $item['icon_bg'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $item['color'] }}" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">{!! $item['icon'] !!}</svg>
                        </div>
                        <div>
                            <div class="pipeline-count" style="color: {{ $item['color'] }}">{{ $item['count'] }}</div>
                            <div class="pipeline-lbl">{{ $item['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- ── RECENT ORDERS + LOW STOCK ───────────────────────────────────────── --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Recent Orders Table --}}
        <div class="panel xl:col-span-2">
            <div class="panel-header">
                <div>
                    <p class="panel-title">🛒 Recent Orders</p>
                    <p class="panel-sub">Latest {{ $recentOrders->count() }} transactions across the system</p>
                </div>
                <a href="{{ route('order-management.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors no-underline">
                    View All →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="db-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer / Agent</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th class="text-right">When</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <span class="font-bold text-slate-800 text-xs font-mono">{{ $order->order_number }}</span>
                                </td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-800 text-xs leading-tight">
                                            {{ $order->customer->name ?? ($order->agent->agent_name ?? 'Walk-in Customer') }}
                                        </span>
                                        <span class="text-slate-400 text-[10px]">
                                            {{ $order->customer->phone ?? ($order->agent->phone ?? '—') }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-bold text-slate-900 text-xs">Rs {{ number_format($order->grand_total, 2) }}</span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $order->status_color }}">
                                        <span class="status-dot" style="background: {{ $order->status_dot }}"></span>
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text-slate-400 text-[11px]">{{ $order->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-slate-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                        <p class="font-semibold text-sm">No orders found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Low Stock Alerts --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <p class="panel-title" style="color:#dc2626;">⚠️ Low Stock Alerts</p>
                    <p class="panel-sub">Items below minimum threshold</p>
                </div>
                <a href="{{ route('inventoryManagement.index') }}" class="text-xs font-semibold text-red-500 hover:text-red-600 transition-colors no-underline">
                    Manage →
                </a>
            </div>
            <div class="p-4 flex flex-col gap-2">
                @forelse($lowStockItems as $stock)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-red-50/60 border border-red-100 hover:border-red-200 transition-colors">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 text-xs m-0 truncate">{{ $stock->productItem->product_name ?? 'Unknown Item' }}</p>
                                <p class="text-slate-400 text-[10px] m-0">SKU #{{ $stock->productItem->id ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-2">
                            <p class="font-black text-red-600 text-base leading-none m-0">{{ number_format($stock->quantity, 0) }}</p>
                            <p class="text-slate-400 text-[10px] m-0 uppercase font-semibold">left</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-10 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <p class="font-bold text-emerald-500 text-sm mt-2 m-0">Stock levels are healthy!</p>
                        <p class="text-slate-400 text-xs mt-1">All items above minimum threshold</p>
                    </div>
                @endforelse
            </div>
            @if($lowStockItems->count() > 0)
                <div class="p-4 border-t border-gray-50">
                    <a href="{{ route('inventoryManagement.index') }}"
                       class="flex items-center justify-center gap-2 w-full py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-colors no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Restock Inventory
                    </a>
                </div>
            @endif
        </div>

    </div>

    {{-- ── MONTHLY CHART + TOP AGENTS + PRODUCTION ─────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Monthly Revenue Chart (last 6 months) --}}
        <div class="panel lg:col-span-1">
            <div class="panel-header">
                <div>
                    <p class="panel-title">📈 Monthly Revenue</p>
                    <p class="panel-sub">Last 6 months comparison</p>
                </div>
            </div>
            <div class="p-5">
                @php $maxMon = collect($monthlyRevenue)->max('revenue') ?: 1; @endphp
                <div class="flex flex-col gap-3">
                    @foreach($monthlyRevenue as $mon)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-semibold text-slate-600">{{ $mon['label'] }}</span>
                                <span class="text-xs font-bold text-slate-800">Rs {{ number_format($mon['revenue'], 0) }}</span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-bar db-gradient-indigo"
                                     style="width: {{ $maxMon > 0 ? round(($mon['revenue']/$maxMon)*100) : 0 }}%; background: linear-gradient(90deg,#4f46e5,#818cf8);">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Top Agents This Month --}}
        <div class="panel lg:col-span-1">
            <div class="panel-header">
                <div>
                    <p class="panel-title">🏆 Top Agents</p>
                    <p class="panel-sub">By sales this month</p>
                </div>
                <a href="{{ route('agentManagement.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors no-underline">
                    All →
                </a>
            </div>
            <div class="p-5">
                @php
                    $avatarColors = ['#4f46e5','#059669','#d97706','#dc2626','#7c3aed'];
                    $rankEmoji = ['🥇','🥈','🥉','4️⃣','5️⃣'];
                    $topMax = collect($topAgents)->max('total_sales') ?: 1;
                @endphp
                @forelse($topAgents as $i => $agent)
                    <div class="agent-row">
                        <div class="agent-avatar" style="background: {{ $avatarColors[$i % 5] }}">
                            {{ strtoupper(substr($agent->agent_name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs font-bold text-slate-800 truncate">
                                    {{ $rankEmoji[$i] ?? ($i+1).'.&nbsp;' }} {{ $agent->agent_name }}
                                </span>
                                <span class="text-xs font-black text-slate-900 flex-shrink-0 ml-2">
                                    Rs {{ number_format($agent->total_sales, 0) }}
                                </span>
                            </div>
                            <div class="prog-track">
                                <div class="prog-bar" style="width: {{ $topMax > 0 ? round(($agent->total_sales/$topMax)*100) : 0 }}%; background: {{ $avatarColors[$i % 5] }};"></div>
                            </div>
                            <span class="text-[10px] text-slate-400">{{ $agent->order_count }} orders</span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
                        <p class="text-xs font-semibold mt-2">No agent sales yet this month</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Today's Production Schedule --}}
        <div class="panel lg:col-span-1">
            <div class="panel-header">
                <div>
                    <p class="panel-title">🏭 Today's Production</p>
                    <p class="panel-sub">Scheduled batches for today</p>
                </div>
                <a href="{{ route('advancedPlanner.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors no-underline">
                    Planner →
                </a>
            </div>
            <div class="p-4 flex flex-col gap-2">
                @forelse($todayProduction as $sched)
                    @php
                        $statusLabel = match($sched->status) {
                            0 => 'Scheduled', 1 => 'In Progress', 2 => 'Completed', 3 => 'Cancelled', default => 'Unknown'
                        };
                        $statusStyle = match($sched->status) {
                            0 => 'bg-amber-100 text-amber-700',
                            1 => 'bg-blue-100 text-blue-700',
                            2 => 'bg-green-100 text-green-700',
                            3 => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700'
                        };
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition-colors">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-slate-800 text-xs m-0 truncate">{{ $sched->productItem->product_name ?? 'Unknown Product' }}</p>
                                <p class="text-slate-400 text-[10px] m-0">
                                    {{ $sched->start_time ? $sched->start_time->format('h:i A') : '—' }}
                                    · Qty: {{ number_format($sched->quantity, 0) }}
                                </p>
                            </div>
                        </div>
                        <span class="status-badge {{ $statusStyle }} flex-shrink-0 ml-2">{{ $statusLabel }}</span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8 text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <p class="text-xs font-semibold mt-2">No production scheduled today</p>
                        <a href="{{ route('advancedPlanner.index') }}" class="text-xs text-indigo-500 mt-1 font-semibold no-underline hover:underline">Schedule Now →</a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ── QUICK ACTION SHORTCUTS ───────────────────────────────────────────── --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-title">⚡ Quick Actions</p>
                <p class="panel-sub">Jump to the most used areas of the system</p>
            </div>
        </div>
        <div class="p-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            @php
                $quickLinks = [
                    ['label' => 'New Order',        'route' => route('order-management.index'),       'icon' => '<path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',                               'bg' => '#eff6ff', 'color' => '#2563eb'],
                    ['label' => 'Product Master',              'route' => route('productManagement.index'),                    'icon' => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>',                              'bg' => '#f0fdf4', 'color' => '#16a34a'],
                    ['label' => 'Financial Management',        'route' => route('agent-financial-management.index'),    'icon' => '<path d="M21 9a9 9 0 0 0-9-9 9 9 0 0 0-9 9 9 9 0 0 0 9 9 9 9 0 0 0 9-9z"/><path d="M9 12h.01"/><path d="M15 12h.01"/>',  'bg' => '#fefce8', 'color' => '#ca8a04'],
                    ['label' => 'Route Management',       'route' => route('routeManagement.index'),        'icon' => '<circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/>',                                                      'bg' => '#eef2ff', 'color' => '#4f46e5'],
                    ['label' => 'Agents',           'route' => route('agentManagement.index'),        'icon' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>',  'bg' => '#fdf4ff', 'color' => '#9333ea'],
                    ['label' => 'Daily Summary',    'route' => route('analyticsReportsDailySummary.index'), 'icon' => '<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>',                                    'bg' => '#fff1f2', 'color' => '#be123c'],
                ];
            @endphp
            @foreach($quickLinks as $link)
                <a href="{{ $link['route'] }}" class="no-underline group flex flex-col items-center gap-2 p-4 rounded-xl border border-transparent hover:border-slate-200 transition-all hover:shadow-sm"
                   style="background: {{ $link['bg'] }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: {{ $link['color'] }}20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="{{ $link['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $link['icon'] !!}</svg>
                    </div>
                    <span class="text-xs font-bold text-slate-700 text-center">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

</div>

{{-- Animate progress bars on load --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Animate progress bars
        document.querySelectorAll('.prog-bar').forEach(bar => {
            const targetWidth = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => { bar.style.width = targetWidth; }, 200);
        });
    });
</script>

@endsection
