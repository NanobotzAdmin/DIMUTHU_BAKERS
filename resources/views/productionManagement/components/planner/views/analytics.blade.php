<div class="flex-1 p-6 bg-gray-50 overflow-auto h-full">
    <div class="mx-auto">

        <div class="mb-4 flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Production Analytics</h2>
                <p class="text-sm text-gray-600">Performance metrics, predictive insights, and cost analysis</p>
            </div>
            <button onclick="exportReport()"
                class="flex items-center gap-2 px-3 py-2 text-sm font-medium border border-gray-300 rounded-md hover:bg-gray-50 bg-white transition-colors">
                <i data-lucide="download" class="w-4 h-4"></i>
                Export PDF
            </button>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6 flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <i data-lucide="filter" class="w-4 h-4 text-gray-400"></i>
                <span class="text-sm text-gray-700">Filters:</span>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-600">Period:</label>
                <select
                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-[#D4A017]">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-600">Department:</label>
                <select
                    class="text-sm border border-gray-300 rounded px-2 py-1 w-[180px] focus:outline-none focus:border-[#D4A017]">
                    <option value="all">All Departments</option>
                    <option value="main">Main Bakery</option>
                    <option value="pastry">Pastry</option>
                </select>
            </div>

            <button class="text-sm text-gray-500 hover:text-gray-900 ml-auto">Clear Filters</button>
        </div>

        <div class="flex gap-2 mb-6 border-b border-gray-200">
            <button onclick="switchTab('performance')"
                class="tab-btn active px-4 py-2 text-sm transition-colors relative group text-[#D4A017]"
                id="btn-performance">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                    Performance
                </div>
                <div class="tab-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-[#D4A017] transition-colors"></div>
            </button>
            <button onclick="switchTab('predictive')"
                class="tab-btn px-4 py-2 text-sm transition-colors relative group text-gray-600 hover:text-gray-900"
                id="btn-predictive">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="zap" class="w-4 h-4"></i>
                    Predictive Analytics
                </div>
                <div class="tab-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-transparent transition-colors">
                </div>
            </button>
            <button onclick="switchTab('costs')"
                class="tab-btn px-4 py-2 text-sm transition-colors relative group text-gray-600 hover:text-gray-900"
                id="btn-costs">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                    Cost Analysis
                </div>
                <div class="tab-indicator absolute bottom-0 left-0 right-0 h-0.5 bg-transparent transition-colors">
                </div>
            </button>
        </div>

        <div id="tab-content-performance" class="space-y-6 animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-gray-400"></i>
                        <span class="text-xs font-medium text-green-600">+12%</span>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">184</div>
                    <div class="text-xs text-gray-600">Tasks Completed</div>
                    <div class="text-xs text-gray-500 mt-1">This week</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="clock" class="w-5 h-5 text-gray-400"></i>
                        <span class="text-xs font-medium text-green-600">-8%</span>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">2.4h</div>
                    <div class="text-xs text-gray-600">Avg. Task Duration</div>
                    <div class="text-xs text-gray-500 mt-1">Per task</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="trending-up" class="w-5 h-5 text-gray-400"></i>
                        <span class="text-xs font-medium text-green-600">+5%</span>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">78%</div>
                    <div class="text-xs text-gray-600">Avg. Utilization</div>
                    <div class="text-xs text-gray-500 mt-1">All resources</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-gray-400"></i>
                        <span class="text-xs font-medium text-green-600">-15</span>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">3</div>
                    <div class="text-xs text-gray-600">Active Conflicts</div>
                    <div class="text-xs text-gray-500 mt-1">Requires attention</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Resource Utilization Trends</h3>
                    <div class="h-[280px]">
                        <canvas id="utilizationChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Tasks: Planned vs Completed</h3>
                    <div class="h-[280px]">
                        <canvas id="tasksChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Conflict Detection & Resolution</h3>
                    <div class="h-[280px]">
                        <canvas id="conflictChart"></canvas>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">On-Time Completion Rate</h3>
                    <div class="h-[250px] flex justify-center">
                        <canvas id="onTimeChart"></canvas>
                    </div>
                    <div class="flex justify-center gap-6 mt-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-xs text-gray-600">On Time (85%)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                            <span class="text-xs text-gray-600">Delayed (11%)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span class="text-xs text-gray-600">Late (4%)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Department Performance Summary</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs text-gray-600 font-medium">Department</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600 font-medium">Efficiency</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600 font-medium">Avg. Duration</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600 font-medium">Tasks</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-sm text-gray-900">Main Bakery</td>
                                <td class="py-3 px-4 text-right"><span class="text-sm">92%</span></td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">2.4h</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">68</td>
                                <td class="py-3 px-4 text-right"><span
                                        class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Excellent</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-sm text-gray-900">Pastry</td>
                                <td class="py-3 px-4 text-right"><span class="text-sm">88%</span></td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">3.1h</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">42</td>
                                <td class="py-3 px-4 text-right"><span
                                        class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-medium">Excellent</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 text-sm text-gray-900">Decoration</td>
                                <td class="py-3 px-4 text-right"><span class="text-sm">76%</span></td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">2.8h</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">35</td>
                                <td class="py-3 px-4 text-right"><span
                                        class="bg-amber-100 text-amber-700 px-2 py-1 rounded text-xs font-medium">Good</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="tab-content-predictive" class="space-y-6 hidden animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200 p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="zap" class="w-5 h-5 text-blue-600"></i>
                        <span class="text-sm text-blue-900">Capacity Forecast</span>
                    </div>
                    <div class="text-2xl font-semibold text-blue-900 mb-1">3 Days</div>
                    <div class="text-xs text-blue-700">Will exceed 85% threshold</div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg border border-amber-200 p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                        <span class="text-sm text-amber-900">Predicted Bottlenecks</span>
                    </div>
                    <div class="text-2xl font-semibold text-amber-900 mb-1">2 Critical</div>
                    <div class="text-xs text-amber-700">Resources at risk</div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200 p-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i data-lucide="target" class="w-5 h-5 text-green-600"></i>
                        <span class="text-sm text-green-900">Optimization Potential</span>
                    </div>
                    <div class="text-2xl font-semibold text-green-900 mb-1">9.8 hrs</div>
                    <div class="text-xs text-green-700">Savable through optimization</div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-900">7-Day Capacity Forecast</h3>
                    <span class="text-xs border px-2 py-1 rounded bg-gray-50">Confidence: 87%</span>
                </div>
                <div class="h-[280px]">
                    <canvas id="forecastChart"></canvas>
                </div>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600 mt-0.5"></i>
                    <p class="text-xs text-amber-900"><strong>Alert:</strong> Wed-Fri predicted to exceed capacity
                        threshold. Consider redistributing tasks.</p>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">AI-Powered Optimization Suggestions</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-[#D4A017] bg-opacity-10 flex items-center justify-center">
                                <i data-lucide="zap" class="w-4 h-4 text-[#D4A017]"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Redistribute Friday Load</p>
                                <p class="text-xs text-gray-600">Impact: High • Savings: 4.5h</p>
                            </div>
                        </div>
                        <button
                            class="text-xs border border-gray-300 rounded px-3 py-1 hover:bg-gray-100">Apply</button>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-[#D4A017] bg-opacity-10 flex items-center justify-center">
                                <i data-lucide="zap" class="w-4 h-4 text-[#D4A017]"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-900">Adjust Decoration Shift Times</p>
                                <p class="text-xs text-gray-600">Impact: Medium • Savings: 2.2h</p>
                            </div>
                        </div>
                        <button
                            class="text-xs border border-gray-300 rounded px-3 py-1 hover:bg-gray-100">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-content-costs" class="space-y-6 hidden animate-fade-in">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="users" class="w-5 h-5 text-blue-500"></i>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">Rs. 12,450</div>
                    <div class="text-xs text-gray-600">Total Labor Cost</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="package" class="w-5 h-5 text-orange-500"></i>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">Rs. 8,920</div>
                    <div class="text-xs text-gray-600">Material Cost</div>
                </div>
                <div class="bg-white rounded-lg border border-gray-200 p-4">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="layers" class="w-5 h-5 text-purple-500"></i>
                    </div>
                    <div class="text-2xl text-gray-900 mb-1">Rs. 3,560</div>
                    <div class="text-xs text-gray-600">Overhead Allocation</div>
                </div>
                <div class="bg-gradient-to-br from-[#D4A017] to-[#B8860B] rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between mb-2">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="text-2xl mb-1">Rs. 24,930</div>
                    <div class="text-xs opacity-90">Total Production Cost</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Cost Category Breakdown</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700">Direct Labor</span>
                                <span class="text-gray-900 font-medium">35.8%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#D4A017] h-2 rounded-full" style="width: 35.8%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700">Raw Materials</span>
                                <span class="text-gray-900 font-medium">35.8%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#D4A017] h-2 rounded-full" style="width: 35.8%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-700">Overhead</span>
                                <span class="text-gray-900 font-medium">14.3%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-[#D4A017] h-2 rounded-full" style="width: 14.3%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">Cost Metrics</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Average Cost Per Task</span>
                            <span class="text-sm text-gray-900">Rs. 135.60</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Total Tasks (This Week)</span>
                            <span class="text-sm text-gray-900">184</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Labor Cost %</span>
                            <span class="text-sm text-gray-900">49.9%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Material Cost %</span>
                            <span class="text-sm text-gray-900">35.8%</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm text-gray-700">Overhead %</span>
                            <span class="text-sm text-gray-900">14.3%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Department Cost Analysis</h3>
                <div class="h-[300px]">
                    <canvas id="costChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-sm font-medium text-gray-900 mb-4">Detailed Department Costs</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 text-xs text-gray-600">Department</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600">Total Cost</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600">Tasks</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600">Cost/Task</th>
                                <th class="text-right py-3 px-4 text-xs text-gray-600">% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 flex items-center gap-2">
                                    <i data-lucide="flame" class="w-4 h-4 text-orange-500"></i>
                                    <span class="text-sm text-gray-900">Main Bakery</span>
                                </td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 9,845</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">68</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 144.78</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">39.5%</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 flex items-center gap-2">
                                    <i data-lucide="chef-hat" class="w-4 h-4 text-blue-500"></i>
                                    <span class="text-sm text-gray-900">Pastry</span>
                                </td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 7,230</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">42</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 172.14</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">29.0%</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 flex items-center gap-2">
                                    <i data-lucide="users" class="w-4 h-4 text-purple-500"></i>
                                    <span class="text-sm text-gray-900">Decoration</span>
                                </td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 4,125</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">35</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 117.86</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">16.5%</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3 px-4 flex items-center gap-2">
                                    <i data-lucide="layers" class="w-4 h-4 text-green-500"></i>
                                    <span class="text-sm text-gray-900">Prep Kitchen</span>
                                </td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 3,730</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">39</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-900">Rs. 95.64</td>
                                <td class="py-3 px-4 text-right text-sm text-gray-600">15.0%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg border border-blue-200 p-6">
                <div class="flex items-start gap-3">
                    <i data-lucide="file-text" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900 mb-2">GL Integration Summary</h3>
                        <p class="text-xs text-blue-800 mb-4">All production costs are automatically posted to the
                            General Ledger with proper account mapping.</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                            <div class="bg-white bg-opacity-60 rounded p-3">
                                <div class="text-blue-900 mb-1">Work in Progress (WIP)</div>
                                <div class="text-blue-700">Account: 1300 - WIP Inventory</div>
                            </div>
                            <div class="bg-white bg-opacity-60 rounded p-3">
                                <div class="text-blue-900 mb-1">Direct Labor</div>
                                <div class="text-blue-700">Account: 5100 - Labor Costs</div>
                            </div>
                            <div class="bg-white bg-opacity-60 rounded p-3">
                                <div class="text-blue-900 mb-1">Overhead Applied</div>
                                <div class="text-blue-700">Account: 5300 - Overhead</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Component Initialization Logic
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // 2. Tab Switching Logic
        window.switchTab = function (tabName) {
            // Hide all tab contents
            document.getElementById('tab-content-performance').classList.add('hidden');
            document.getElementById('tab-content-predictive').classList.add('hidden');
            document.getElementById('tab-content-costs').classList.add('hidden');

            // Show selected content
            document.getElementById('tab-content-' + tabName).classList.remove('hidden');

            // Reset all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'text-[#D4A017]');
                btn.classList.add('text-gray-600');
                btn.querySelector('.tab-indicator').classList.remove('bg-[#D4A017]');
                btn.querySelector('.tab-indicator').classList.add('bg-transparent');
            });

            // Activate specific button
            const activeBtn = document.getElementById('btn-' + tabName);
            activeBtn.classList.add('active', 'text-[#D4A017]');
            activeBtn.classList.remove('text-gray-600');
            activeBtn.querySelector('.tab-indicator').classList.add('bg-[#D4A017]');
            activeBtn.querySelector('.tab-indicator').classList.remove('bg-transparent');
        };

        // 3. Export Mock Function
        window.exportReport = function () {
            alert('Analytics_Report_Jan_2026.pdf downloaded successfully');
        };

        // 4. Chart.js Initialization
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { borderDash: [2, 4], color: '#f3f4f6' }, beginAtZero: true }
            }
        };

        // Initialize Charts if their canvas elements exist
        const ctxUtilization = document.getElementById('utilizationChart');
        if (ctxUtilization) {
            new Chart(ctxUtilization, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        { label: 'Main Bakery', data: [75, 82, 78, 88, 92, 95, 72], borderColor: '#F97316', tension: 0.4 },
                        { label: 'Pastry', data: [68, 72, 85, 78, 88, 92, 65], borderColor: '#3B82F6', tension: 0.4 },
                        { label: 'Decoration', data: [45, 52, 60, 48, 65, 70, 55], borderColor: '#8B5CF6', tension: 0.4 },
                        { label: 'Prep', data: [62, 68, 70, 75, 82, 85, 60], borderColor: '#10B981', tension: 0.4 }
                    ]
                },
                options: commonOptions
            });
        }

        const ctxTasks = document.getElementById('tasksChart');
        if (ctxTasks) {
            new Chart(ctxTasks, {
                type: 'bar',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [
                        { label: 'Planned', data: [145, 152, 148, 160], backgroundColor: '#D4A017' },
                        { label: 'Completed', data: [138, 149, 142, 156], backgroundColor: '#10B981' },
                        { label: 'Delayed', data: [7, 3, 6, 4], backgroundColor: '#EF4444' }
                    ]
                },
                options: commonOptions
            });
        }

        const ctxConflict = document.getElementById('conflictChart');
        if (ctxConflict) {
            new Chart(ctxConflict, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        { label: 'Total Conflicts', data: [12, 8, 15, 6, 18, 22, 10], borderColor: '#F59E0B', backgroundColor: 'rgba(245, 158, 11, 0.2)', fill: true, tension: 0.4 },
                        { label: 'Resolved', data: [10, 8, 12, 6, 15, 18, 9], borderColor: '#10B981', backgroundColor: 'rgba(16, 185, 129, 0.2)', fill: true, tension: 0.4 }
                    ]
                },
                options: commonOptions
            });
        }

        const ctxOnTime = document.getElementById('onTimeChart');
        if (ctxOnTime) {
            new Chart(ctxOnTime, {
                type: 'doughnut',
                data: {
                    labels: ['On Time', 'Delayed', 'Late'],
                    datasets: [{
                        data: [156, 20, 8],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        const ctxForecast = document.getElementById('forecastChart');
        if (ctxForecast) {
            new Chart(ctxForecast, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [
                        { label: 'Predicted Utilization', data: [78, 82, 91, 88, 95, 72, 65], borderColor: '#3B82F6', backgroundColor: '#DBEAFE', fill: true, tension: 0.4 },
                        { label: 'Threshold (85%)', data: [85, 85, 85, 85, 85, 85, 85], borderColor: '#EF4444', borderDash: [5, 5], pointRadius: 0, fill: false }
                    ]
                },
                options: commonOptions
            });
        }

        const ctxCost = document.getElementById('costChart');
        if (ctxCost) {
            new Chart(ctxCost, {
                type: 'bar',
                data: {
                    labels: ['Main Bakery', 'Pastry', 'Decoration', 'Prep Kitchen'],
                    datasets: [{
                        label: 'Total Cost (Rs.)',
                        data: [9845, 7230, 4125, 3730],
                        backgroundColor: '#D4A017',
                        borderRadius: 4
                    }]
                },
                options: commonOptions
            });
        }
    });
</script>