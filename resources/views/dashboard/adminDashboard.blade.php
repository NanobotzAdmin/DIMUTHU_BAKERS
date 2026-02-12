@extends('layouts.app')

@section('title', 'Command Center')

@section('content')

    <div class="grid grid-cols-1 gap-6 w-full">

        <nav class="col-span-1  flex justify-between items-center mb-2">
            <div class="page-title">
                <h4 class="font-bold text-2xl text-gray-800 m-0">@yield('title', 'Dashboard')</h4>
                <!-- Dynamic Date if possible, or static placeholder -->
                <p class="text-gray-500 text-sm m-0">Saturday, November 30, 2025 • Live View</p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Search Icon -->
                <button
                    class="bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full p-2 flex items-center justify-center w-10 h-10 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>

                <!-- Custom Buttons -->
                <button
                    class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-medium transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                    Customize Dashboard
                </button>

                <button
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg hover:bg-emerald-100 text-sm font-medium transition-colors shadow-sm">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
                    All Systems Operational
                </button>
            </div>
        </nav>

        <div class="flex gap-6 w-full">
            <!-- COLUMN 1 -->
            <div class="flex flex-col gap-6 w-full">
                <!-- Waste Watch (Redesigned) -->
                <div class="bg-[#B91C1C] rounded-2xl shadow-sm p-5 relative overflow-hidden text-white">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-2 text-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            <h6 class="m-0 uppercase font-bold tracking-wider text-sm">Waste Watch</h6>
                        </div>
                        <span class="text-xs text-red-200">Now</span>
                    </div>

                    <div class="mb-6">
                        <h1 class="text-4xl font-bold text-white">Rs 450</h1>
                        <div class="flex items-center gap-2 mt-2">
                            <span
                                class="flex items-center gap-1 text-[#B91C1C] bg-white/90 px-2 py-0.5 rounded text-sm font-bold">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                                    <polyline points="17 18 23 18 23 12"></polyline>
                                </svg>
                                19.22%
                            </span>
                            <span class="text-red-100 text-sm">vs yesterday Rs 524</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h6 class="text-xs uppercase text-red-200 font-semibold mb-3">Top Categories</h6>
                        <div class="flex gap-2">
                            <div class="bg-red-800/50 rounded-lg p-3 flex-1 border border-red-500/30">
                                <div class="flex items-center gap-1 mb-1">
                                    <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                    <span class="text-xs font-bold text-red-100">Bakehouse</span>
                                </div>
                                <div class="text-lg font-bold text-white leading-none">45%</div>
                                <div class="text-xs text-red-200 mt-1">Rs 180</div>
                            </div>
                            <div class="bg-red-800/50 rounded-lg p-3 flex-1 border border-red-500/30">
                                <div class="flex items-center gap-1 mb-1">
                                    <div class="w-2 h-2 rounded-full bg-pink-400"></div>
                                    <span class="text-xs font-bold text-red-100">Cakes</span>
                                </div>
                                <div class="text-lg font-bold text-white leading-none">32%</div>
                                <div class="text-xs text-red-200 mt-1">Rs 150</div>
                            </div>
                            <div class="bg-red-800/50 rounded-lg p-3 flex-1 border border-red-500/30">
                                <div class="flex items-center gap-1 mb-1">
                                    <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                                    <span class="text-xs font-bold text-red-100">Pastry</span>
                                </div>
                                <div class="text-lg font-bold text-white leading-none">23%</div>
                                <div class="text-xs text-red-200 mt-1">Rs 120</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h6 class="text-xs uppercase text-red-200 font-semibold mb-3">Top Waste Items</h6>
                        <div class="flex justify-between items-center py-2 border-b border-red-500/30 last:border-0">
                            <span class="text-sm text-red-100">• Burnt Dinner Rolls (12)</span>
                            <span class="font-bold text-sm text-white">Rs 180</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-red-500/30 last:border-0">
                            <span class="text-sm text-red-100">• Expired Cream (2)</span>
                            <span class="font-bold text-sm text-white">Rs 150</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-red-500/30 last:border-0">
                            <span class="text-sm text-red-100">• Damaged Cakes (3)</span>
                            <span class="font-bold text-sm text-white">Rs 120</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <button
                            class="w-full px-6 py-2 rounded-lg border border-red-400/50 text-sm font-medium text-white hover:bg-red-800/50 transition-colors flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            View Waste Analysis →
                        </button>
                    </div>
                </div>

                <!-- Financial Health (Moved here) -->
                <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                <polyline points="17 6 23 6 23 12"></polyline>
                            </svg>
                        </div>
                        <div>
                            <h5 class="m-0 font-bold text-gray-900">Financial Health</h5>
                            <p class="m-0 text-gray-500 text-sm">Performance vs Target</p>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-4">
                        <button
                            class="flex-1 px-3 py-1.5 rounded-md bg-white border border-gray-200 text-sm font-bold shadow-sm text-gray-800">Today</button>
                        <button
                            class="flex-1 px-3 py-1.5 rounded-md bg-gray-50 border border-transparent text-sm text-gray-600 hover:bg-gray-100 transition-colors">Week</button>
                        <button
                            class="flex-1 px-3 py-1.5 rounded-md bg-gray-50 border border-transparent text-sm text-gray-600 hover:bg-gray-100 transition-colors">Month</button>
                    </div>

                    <div class="grid grid-cols-4 gap-2">
                        <div class="p-2 rounded-lg bg-gray-50 text-center">
                            <div class="text-[0.65rem] text-gray-400 uppercase font-semibold">Revenue</div>
                            <div class="font-bold text-blue-600 text-sm">Rs 85k</div>
                            <div class="text-[0.6rem] text-gray-400">Target: 91k</div>
                        </div>
                        <div class="p-2 rounded-lg bg-emerald-50 text-center">
                            <div class="text-[0.65rem] text-emerald-600 uppercase font-semibold">Net Profit</div>
                            <div class="font-bold text-emerald-600 text-sm">Rs 29k</div>
                            <div class="text-[0.6rem] text-emerald-500">33.5%</div>
                        </div>
                        <div class="p-2 rounded-lg bg-gray-50 text-center">
                            <div class="text-[0.65rem] text-gray-400 uppercase font-semibold">Expenses</div>
                            <div class="font-bold text-amber-500 text-sm">Rs 56k</div>
                            <div class="text-[0.6rem] text-gray-400">65% Rev</div>
                        </div>
                        <div class="p-2 rounded-lg bg-gray-50 text-center">
                            <div class="text-[0.65rem] text-gray-400 uppercase font-semibold">Margin</div>
                            <div class="font-bold text-purple-600 text-sm">33.5%</div>
                            <div class="text-[0.6rem] text-gray-400">Target: 33%</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-xs uppercase text-gray-400 font-semibold mb-3">Cost Breakdown</h6>
                        <div class="flex justify-between items-end text-sm gap-2">
                            <div class="flex-1">
                                <span class="block text-gray-500 text-xs mb-1">Raw Materials</span>
                                <span class="font-bold text-gray-800">Rs 32k</span>
                                <div class="h-1 w-full bg-gray-100 rounded-full mt-1">
                                    <div class="h-full bg-amber-400 rounded-full" style="width:60%"></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <span class="block text-gray-500 text-xs mb-1">Staff Wages</span>
                                <span class="font-bold text-gray-800">Rs 19k</span>
                                <div class="h-1 w-full bg-gray-100 rounded-full mt-1">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width:30%"></div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <span class="block text-gray-500 text-xs mb-1">Utilities</span>
                                <span class="font-bold text-gray-800">Rs 1k</span>
                                <div class="h-1 w-full bg-gray-100 rounded-full mt-1">
                                    <div class="h-full bg-cyan-500 rounded-full" style="width:10%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Silos (New) -->
                <div class="bg-indigo-900 rounded-2xl shadow-sm p-5 text-white relative overflow-hidden">
                    <!-- Background pattern equivalent -->
                    <div class="absolute inset-0 opacity-10 pointer-events-none"
                        style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;">
                    </div>

                    <div class="flex justify-between items-center mb-4 relative z-10">
                        <div>
                            <h5 class="m-0 font-bold">Inventory Silos</h5>
                            <p class="m-0 text-indigo-200 text-sm">Stock Levels • Total: Rs 45k</p>
                        </div>
                    </div>
                    <div class="flex gap-2 mb-6 relative z-10">
                        <span
                            class="bg-red-500/20 text-red-200 border border-red-500/30 px-2 py-0.5 rounded text-xs font-semibold">5
                            need reorder</span>
                        <span
                            class="bg-amber-500/20 text-amber-200 border border-amber-500/30 px-2 py-0.5 rounded text-xs font-semibold">4
                            expiring soon</span>
                    </div>
                    <div class="flex justify-between items-end mt-4 px-2 relative z-10 h-32">
                        <div class="flex flex-col items-center gap-2 group cursor-pointer">
                            <div
                                class="w-10 bg-indigo-800/50 rounded-lg relative overflow-hidden h-full flex items-end border border-indigo-700/50 group-hover:border-indigo-500 transition-colors">
                                <div class="w-full bg-emerald-500 transition-all duration-1000" style="height: 80%;">
                                </div>
                            </div>
                            <span class="text-xs font-bold text-indigo-100">Flour</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 group cursor-pointer">
                            <div
                                class="w-10 bg-indigo-800/50 rounded-lg relative overflow-hidden h-full flex items-end border border-indigo-700/50 group-hover:border-indigo-500 transition-colors">
                                <div class="w-full bg-amber-400 transition-all duration-1000" style="height: 40%;"></div>
                            </div>
                            <span class="text-xs font-bold text-indigo-100">Sugar</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 group cursor-pointer">
                            <div
                                class="w-10 bg-indigo-800/50 rounded-lg relative overflow-hidden h-full flex items-end border border-indigo-700/50 group-hover:border-indigo-500 transition-colors">
                                <div class="w-full bg-red-400 transition-all duration-1000" style="height: 15%;"></div>
                            </div>
                            <span class="text-xs font-bold text-indigo-100">Butter</span>
                        </div>
                        <div class="flex flex-col items-center gap-2 group cursor-pointer">
                            <div
                                class="w-10 bg-indigo-800/50 rounded-lg relative overflow-hidden h-full flex items-end border border-indigo-700/50 group-hover:border-indigo-500 transition-colors">
                                <div class="w-full bg-emerald-500 transition-all duration-1000" style="height: 90%;">
                                </div>
                            </div>
                            <span class="text-xs font-bold text-indigo-100">Eggs</span>
                        </div>
                        <div
                            class="flex flex-col items-center gap-2 group cursor-pointer opacity-50 hover:opacity-100 transition-opacity">
                            <div
                                class="w-10 bg-indigo-800/50 rounded-lg relative overflow-hidden h-full flex items-end border border-indigo-700/50">
                                <div class="w-full bg-slate-400 transition-all duration-1000" style="height: 20%;"></div>
                            </div>
                            <span class="text-xs font-bold text-indigo-100">Misc</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUMN 2 -->
            <div class="flex flex-col gap-6 w-full">
                <!-- Existing Orders -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center gap-2">
                            <div class="bg-purple-600 text-white rounded-lg p-1.5 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div>
                                <h5 class="m-0 font-bold text-gray-900">Next Big Orders</h5>
                                <p class="m-0 text-gray-500 text-sm">High-Value • Due in 4hrs</p>
                            </div>
                        </div>
                        <a href="#"
                            class="text-sm font-bold text-purple-600 hover:text-purple-700 transition-colors">View All
                            →</a>
                    </div>

                    <!-- Order 1 -->
                    <div class="bg-lime-50 border border-lime-200 rounded-xl p-4 mb-4 relative overflow-hidden">
                        <div class="flex justify-between mb-1">
                            <span class="font-bold text-gray-900">Wedding Cake Order</span>
                            <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">2hrs</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-3 font-medium">Jane Wedding</div>

                        <div class="h-1.5 w-full bg-white rounded-full overflow-hidden">
                            <div class="h-full bg-green-700 rounded-full" style="width: 85%;"></div>
                        </div>
                        <div class="flex justify-end mt-1">
                            <small class="text-green-700 font-bold text-xs">85%</small>
                        </div>

                        <div class="flex justify-between items-center mt-3 text-xs">
                            <span class="text-gray-500 font-medium">Due 9:00 AM <span class="text-green-600 ms-1">✓
                                    Materials</span></span>
                            <span class="font-bold text-gray-900">Rs 8,500</span>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button
                                class="flex-1 bg-green-700 text-white text-xs font-bold py-1.5 rounded-lg hover:bg-green-800 transition-colors shadow-sm">✓
                                Mark Complete</button>
                            <button
                                class="px-3 bg-white border border-gray-200 text-gray-600 text-xs font-bold py-1.5 rounded-lg hover:bg-gray-50 transition-colors">Recipe</button>
                        </div>
                        <div class="mt-3 text-xs text-gray-400 flex items-center gap-1 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg> Associated: Santosh P
                        </div>
                    </div>

                    <!-- Order 2 -->
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-4 relative overflow-hidden">
                        <div class="flex justify-between mb-1">
                            <span class="font-bold text-gray-900">Corporate Gift Box (50pcs)</span>
                            <span class="bg-gray-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">4hrs</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-3 font-medium">ABC Corp</div>

                        <div class="h-1.5 w-full bg-white rounded-full overflow-hidden">
                            <div class="h-full bg-orange-600 rounded-full" style="width: 45%;"></div>
                        </div>
                        <div class="flex justify-end mt-1">
                            <small class="text-gray-500 font-bold text-xs">45%</small>
                        </div>

                        <div class="flex justify-between items-center mt-3 text-xs">
                            <span class="text-gray-500 font-medium">Due 2:30 PM <span class="text-green-600 ms-1">✓
                                    Materials</span></span>
                            <span class="font-bold text-gray-900">Rs 12,000</span>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button
                                class="flex-1 bg-orange-600 text-white text-xs font-bold py-1.5 rounded-lg hover:bg-orange-700 transition-colors shadow-sm">▶
                                Continue Production</button>
                            <button
                                class="px-3 bg-white border border-gray-200 text-gray-600 text-xs font-bold py-1.5 rounded-lg hover:bg-gray-50 transition-colors">Reassign</button>
                        </div>
                        <div class="mt-3 text-xs text-gray-400 flex items-center gap-1 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg> Associated: David
                        </div>
                    </div>

                    <!-- Order 3 (New/Visual Filler from image - Birthday Cake) -->
                    <div class="bg-red-50 border border-red-100 rounded-xl p-4 relative overflow-hidden">
                        <div class="flex justify-between mb-1">
                            <span class="font-bold text-gray-900">Birthday Cake 2 Tier</span>
                            <span class="bg-gray-800 text-white text-xs px-2 py-0.5 rounded-full font-bold">5hrs</span>
                        </div>
                        <div class="text-xs text-gray-500 mb-3 font-medium">Priya Pankaj</div>

                        <div class="h-1.5 w-full bg-white rounded-full overflow-hidden">
                            <div class="h-full bg-orange-600 rounded-full" style="width: 0%;"></div>
                        </div>
                        <div class="flex justify-end mt-1">
                            <small class="text-red-600 font-bold text-xs">0%</small>
                        </div>

                        <div class="flex justify-between items-center mt-3 text-xs">
                            <span class="text-gray-500 font-medium">Due 4:00 PM <span
                                    class="text-red-500 ms-1 font-bold">(!) Materials Missing</span></span>
                            <span class="font-bold text-gray-900">Rs 4,500</span>
                        </div>

                        <div class="flex gap-2 mt-4">
                            <button
                                class="flex-1 bg-red-700 text-white text-xs font-bold py-1.5 rounded-lg hover:bg-red-800 transition-colors shadow-sm">(!)
                                Check Materials</button>
                            <button
                                class="px-3 bg-white border border-gray-200 text-gray-600 text-xs font-bold py-1.5 rounded-lg hover:bg-gray-50 transition-colors">Order
                                Stock</button>
                        </div>
                    </div>

                </div>

                <!-- The Pulse (New) -->
                <div class="bg-gray-900 rounded-2xl shadow-sm p-5 text-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <div
                                class="rounded-full bg-amber-500 text-white p-1.5 flex items-center justify-center w-8 h-8 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="m-0 font-bold text-white">The Pulse</h5>
                                <p class="m-0 text-gray-400 text-sm">Sales Intensity by Hour</p>
                            </div>
                        </div>
                        <button
                            class="px-3 py-1 text-xs rounded-md border border-gray-700 hover:bg-gray-800 transition-colors">Show
                            Yesterday</button>
                    </div>

                    <div class="grid grid-cols-5 gap-2 items-end h-[120px] mb-2 px-2">
                        <div class="flex flex-col items-center justify-end h-full gap-2">
                            <div class="w-full bg-amber-200 rounded-t-sm relative group" style="height:40%;">
                                <div
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rs 2.0k</div>
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">6AM</span>
                        </div>
                        <div class="flex flex-col items-center justify-end h-full gap-2">
                            <div class="w-full bg-amber-300 rounded-t-sm relative group" style="height:55%;">
                                <div
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rs 3.5k</div>
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">7AM</span>
                        </div>
                        <div class="flex flex-col items-center justify-end h-full gap-2">
                            <div class="w-full bg-amber-500 rounded-t-sm relative group" style="height:70%;">
                                <div
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rs 5.5k</div>
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">8AM</span>
                        </div>
                        <div class="flex flex-col items-center justify-end h-full gap-2">
                            <div class="w-full bg-orange-500 rounded-t-sm relative group" style="height:85%;">
                                <div
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rs 7.2k</div>
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">9AM</span>
                        </div>
                        <div class="flex flex-col items-center justify-end h-full gap-2">
                            <div class="w-full bg-red-600 rounded-t-sm relative group" style="height:100%;">
                                <div
                                    class="absolute -top-6 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] px-1 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                    Rs 12.0k</div>
                            </div>
                            <span class="text-[10px] text-gray-500 font-medium">10AM</span>
                        </div>
                    </div>

                    <div class="mt-4 text-xs text-gray-400 border-t border-gray-800 pt-3">
                        <strong class="text-amber-500 uppercase tracking-wide">◎ FORECAST (NEXT 24HR)</strong><br>
                        <span class="mt-1 block">Expected revenue: Rs 4,800 - Rs 5,200</span>
                    </div>
                </div>
            </div>
            
            <!-- COLUMN 3 -->
            <div class="flex flex-col gap-6 w-full">
                <!-- Staff & Shifts (Existing) -->
                <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <div
                                class="bg-cyan-500 text-white rounded-full p-1.5 flex items-center justify-center w-8 h-8 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="m-0 font-bold text-gray-900">Staff & Shifts</h5>
                                <p class="m-0 text-gray-500 text-sm">Live Status & Productivity</p>
                            </div>
                        </div>
                        <a href="#"
                            class="text-sm font-bold text-cyan-600 hover:text-cyan-700 transition-colors">Manage →</a>
                    </div>

                    <!-- Shift Grid -->
                    <div class="mb-4">
                        <h6 class="text-xs uppercase text-gray-400 font-semibold mb-2">Today's Staff</h6>
                        <div class="grid grid-cols-8 gap-1 mb-2">
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-green-100 text-green-700 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✓</div>
                            <div
                                class="bg-gray-100 text-gray-400 aspect-square rounded flex items-center justify-center text-xs font-bold">
                                ✕</div>
                        </div>
                        <div class="flex justify-between mt-2 text-xs font-semibold">
                            <span class="text-green-600">✔ Present: 7</span>
                            <span class="text-red-500">✖ Scheduled: 1</span>
                        </div>
                    </div>

                    <!-- Alerts -->
                    <div class="grid grid-cols-2 gap-2 mt-2 mb-6">
                        <div class="col-span-1">
                            <div class="p-3 rounded-lg bg-red-50 border border-red-100 h-full">
                                <div class="text-xs font-bold text-red-600 mb-1">(!) Overtime Alert</div>
                                <div class="text-[10px] text-gray-500">1 staff approaching OT</div>
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="p-3 rounded-lg bg-green-50 border border-green-100 h-full">
                                <div class="text-xs font-bold text-green-600 mb-1">✔ Productivity</div>
                                <div class="text-[10px] text-gray-500">75 orders today</div>
                            </div>
                        </div>
                    </div>

                    <!-- Staff List -->
                    <div class="border-t border-gray-50 pt-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-full bg-orange-600 text-white flex items-center justify-center font-bold shadow-sm">
                                S</div>
                            <div class="flex-1">
                                <h6 class="m-0 font-bold text-gray-900 text-sm">Santosh</h6>
                                <span class="text-xs text-gray-500">Pastry Chef</span>
                            </div>
                            <div class="text-end">
                                <h6 class="m-0 font-bold text-gray-900 text-sm">4/5</h6>
                                <span class="text-xs text-gray-500">orders</span>
                            </div>
                        </div>
                        <!-- (Truncated other staff for space) -->
                    </div>

                </div>

                <!-- Live Pipeline (New) -->
                <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                    <div class="flex items-center gap-2 mb-4">
                        <div
                            class="bg-blue-600 text-white p-1.5 rounded-full flex items-center justify-center w-8 h-8 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h5 class="m-0 font-bold text-gray-900">Live Pipeline</h5>
                            <p class="m-0 text-gray-500 text-sm">29 Active Orders • 4 Overdue</p>
                        </div>
                    </div>

                    <div>
                        <!-- Prep -->
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wide">
                                Prep</div>
                            <div class="text-xs flex-grow text-gray-500 flex items-center gap-2">
                                <span>⏱ Clears in 30 mins</span>
                                <span class="text-gray-300">|</span>
                                <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="12" height="12" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                    </svg> Nimal, Kamala</span>
                            </div>
                            <div class="h4 font-bold m-0 text-gray-900">8<small
                                    class="text-xs text-gray-400 font-normal block text-right">orders</small></div>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 rounded-full mb-3 overflow-hidden">
                            <div class="h-full bg-gray-500 rounded-full" style="width: 100%"></div>
                        </div>

                        <div class="flex gap-2 mb-4">
                            <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs font-medium">8 On
                                Track</span>
                        </div>

                        <button
                            class="w-full py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors">View
                            Orders</button>
                    </div>
                </div>

                <!-- Oven Status / Bottleneck (New) -->
                <div class="bg-white rounded-2xl shadow-sm p-5 border border-red-200 relative overflow-hidden">
                    <!-- Pulse Animation for Bottleneck -->
                    <div
                        class="absolute top-0 right-0 w-16 h-16 bg-red-500/10 rounded-bl-full z-0 flex items-start justify-end p-2">
                        <div class="w-2 h-2 bg-red-500 rounded-full animate-ping"></div>
                    </div>

                    <div class="flex justify-between items-start relative z-10 mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full bg-orange-600 animate-pulse"></div>
                            <span class="font-bold text-gray-900">Oven</span>
                            <span
                                class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full font-bold border border-red-200 shadow-sm">(!)
                                Bottleneck</span>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">12<small
                                class="text-xs font-normal text-gray-400 block text-right">orders</small></div>
                    </div>
                    <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                        <span>⏱ Clears in 1.5 hrs</span>
                        <span class="text-gray-300">|</span>
                        <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12"
                                height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg> Sunil, Priya, Ravi</span>
                    </div>

                    <div class="flex gap-2 mb-4 relative z-10">
                        <span class="bg-amber-100 text-amber-800 px-2 py-0.5 rounded text-xs font-medium">9 On Track</span>
                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-medium">3 Late</span>
                    </div>

                    <div class="flex gap-2 relative z-10">
                        <button
                            class="flex-1 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">View
                            Orders</button>
                        <button
                            class="flex-1 py-1.5 bg-red-600 text-white rounded-lg text-xs font-bold hover:bg-red-700 transition-colors shadow-sm">Add
                            Staff</button>
                    </div>
                </div>
            </div>
        
        </div>

            

            

        </div>
    @endsection
