@extends('layouts.app')
@section('title', 'Compare Suppliers')

@section('content')

@php
    // 1. DUMMY DATA (Transcribed from PurchaseOrderStore.ts)
    $suppliers = [
        [
            'id' => 'SUP-001',
            'name' => 'Prima Ceylon Flour Mills',
            'contactPerson' => 'Sunil Jayawardena',
            'email' => 'sunil@primaflour.lk',
            'paymentTerms' => 'credit-30',
            'creditLimit' => 500000,
            'rating' => 4.8,
            'totalOrders' => 145,
            'onTimeDelivery' => 96,
            'productsSupplied' => ['All-Purpose Flour', 'Cake Flour', 'Bread Flour'],
            'leadTime' => 2,
            'minimumOrder' => 10000
        ],
        [
            'id' => 'SUP-002',
            'name' => 'Anchor Dairy Products',
            'contactPerson' => 'Nimal Perera',
            'email' => 'nimal@anchor.lk',
            'paymentTerms' => 'credit-15',
            'creditLimit' => 750000,
            'rating' => 4.9,
            'totalOrders' => 234,
            'onTimeDelivery' => 98,
            'productsSupplied' => ['Butter (Salted)', 'Butter (Unsalted)', 'Heavy Cream', 'Milk (Full Cream)'],
            'leadTime' => 1,
            'minimumOrder' => 15000
        ],
        [
            'id' => 'SUP-003',
            'name' => 'Lanka Sugar Company',
            'contactPerson' => 'Kamal Silva',
            'email' => 'kamal@lankasugar.lk',
            'paymentTerms' => 'credit-30',
            'creditLimit' => 400000,
            'rating' => 4.5,
            'totalOrders' => 178,
            'onTimeDelivery' => 92,
            'productsSupplied' => ['White Sugar', 'Brown Sugar', 'Powdered Sugar'],
            'leadTime' => 3,
            'minimumOrder' => 8000
        ],
        [
            'id' => 'SUP-004',
            'name' => 'Global Ingredients Ltd',
            'contactPerson' => 'Chaminda Fernando',
            'email' => 'chaminda@globalingredients.lk',
            'paymentTerms' => 'credit-60',
            'creditLimit' => 1000000,
            'rating' => 4.7,
            'totalOrders' => 98,
            'onTimeDelivery' => 94,
            'productsSupplied' => ['Chocolate Chips', 'Cocoa Powder', 'Dark Chocolate Bars', 'Vanilla Extract'],
            'leadTime' => 5,
            'minimumOrder' => 25000
        ],
        [
            'id' => 'SUP-005',
            'name' => 'Ceylon Yeast Industries',
            'contactPerson' => 'Priyantha Ranasinghe',
            'email' => 'priyantha@ceylonyeast.lk',
            'paymentTerms' => 'credit-7',
            'creditLimit' => 200000,
            'rating' => 4.6,
            'totalOrders' => 87,
            'onTimeDelivery' => 95,
            'productsSupplied' => ['Active Dry Yeast', 'Baking Powder', 'Baking Soda'],
            'leadTime' => 1,
            'minimumOrder' => 5000
        ]
    ];
@endphp

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold flex items-center gap-3 text-gray-900">
            <svg class="w-7 h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
            Compare Suppliers
        </h1>
    </div>

    <div class="space-y-6">
        
        <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
            <div class="flex items-start gap-3 mb-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <div class="flex-1">
                    <h4 class="font-medium text-blue-900 mb-1">Select Suppliers to Compare</h4>
                    <p class="text-sm text-blue-800">
                        Choose up to 4 suppliers to compare side-by-side. Top 3 suppliers shown by default.
                    </p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg p-3 mb-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="supplierSearch" placeholder="Search suppliers by name or products..." class="flex-1 outline-none text-gray-700">
                </div>
            </div>

            <div class="flex flex-wrap gap-2" id="supplierList">
                <!-- Dynamically populated via JS -->
            </div>
        </div>

        <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b-2 border-purple-200">
                        <tr id="comparisonTableHead">
                            <th class="px-5 py-4 text-left text-sm font-medium text-gray-700 w-48 min-w-[200px]">
                                Comparison Criteria
                            </th>
                            <!-- Dynamic Columns -->
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200" id="comparisonTableBody">
                        <!-- Dynamic Rows -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-5 border-2 border-green-200 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-green-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h4 class="font-medium text-green-900 mb-2">Recommendation</h4>
                    <p class="text-green-800 mb-3">
                        Based on overall performance (Rating, Delivery Reliability, and Lead Time), the best suppliers are:
                    </p>
                    <div class="flex flex-wrap gap-2" id="recommendationsList">
                        <!-- Dynamic Recommendations -->
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t-2 border-gray-200">
            <a href="#" class="h-12 px-6 flex items-center bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-xl font-medium shadow-md transition-all">
                Close Comparison
            </a>
        </div>

    </div>
</div>

<style>
    /* Custom Scrollbar for product lists */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

<script>
    $(document).ready(function() {
        const allSuppliers = @json($suppliers);
        let selectedSupplierIds = allSuppliers.slice(0, 3).map(s => s.id); // Default select first 3

        const $supplierList = $('#supplierList');
        const $tableHead = $('#comparisonTableHead');
        const $tableBody = $('#comparisonTableBody');
        const $recommendationsList = $('#recommendationsList');
        const $searchInput = $('#supplierSearch');

        // Helper functions
        const formatCurrency = (val) => 'Rs ' + new Intl.NumberFormat('en-LK').format(val);
        
        function getRatingColor(rating) {
            if (rating >= 4.5) return 'text-green-600';
            if (rating >= 4.0) return 'text-blue-600';
            if (rating >= 3.5) return 'text-yellow-600';
            return 'text-orange-600';
        }

        function getDeliveryColor(percentage) {
            if (percentage >= 95) return 'text-green-600';
            if (percentage >= 90) return 'text-blue-600';
            if (percentage >= 85) return 'text-yellow-600';
            return 'text-orange-600';
        }

        function renderSupplierButtons() {
            $supplierList.empty();
            const searchTerm = $searchInput.val().toLowerCase();

            allSuppliers.forEach(supplier => {
                const isSelected = selectedSupplierIds.includes(supplier.id);
                const matchesSearch = supplier.name.toLowerCase().includes(searchTerm) || 
                                      supplier.productsSupplied.some(p => p.toLowerCase().includes(searchTerm));

                if (!matchesSearch) return;

                const btnClass = isSelected 
                    ? 'bg-purple-500 text-white shadow-sm' 
                    : 'bg-white text-gray-700 hover:bg-gray-100 border-2 border-gray-200';
                
                const checkIcon = isSelected 
                    ? `<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>` 
                    : '';
                
                const removeIcon = isSelected
                    ? `<svg class="w-4 h-4 hover:text-purple-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
                    : '';

                const button = `
                    <button type="button" 
                        class="supplier-btn px-4 py-2 rounded-lg flex items-center gap-2 transition-all ${btnClass}"
                        data-id="${supplier.id}">
                        ${checkIcon}
                        <span class="font-medium">${supplier.name}</span>
                        ${removeIcon}
                    </button>
                `;
                $supplierList.append(button);
            });
        }

        function getBestStats(currentSuppliers) {
            if (currentSuppliers.length === 0) return {};
            return {
                maxRating: Math.max(...currentSuppliers.map(s => s.rating)),
                maxDelivery: Math.max(...currentSuppliers.map(s => s.onTimeDelivery)),
                minLeadTime: Math.min(...currentSuppliers.map(s => s.leadTime)),
                maxOrders: Math.max(...currentSuppliers.map(s => s.totalOrders))
            };
        }

        function renderComparisonTable() {
            // Keep the first "Criteria" column
            const criteriaHeader = `<th class="px-5 py-4 text-left text-sm font-medium text-gray-700 w-48 min-w-[200px]">Comparison Criteria</th>`;
            $tableHead.html(criteriaHeader);
            $tableBody.empty();

            const currentSuppliers = allSuppliers.filter(s => selectedSupplierIds.includes(s.id));
            const stats = getBestStats(currentSuppliers);

            // Render Header Columns
            currentSuppliers.forEach(supplier => {
                const th = `
                    <th class="px-5 py-4 text-center border-l-2 border-purple-100 min-w-[200px]">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center mb-2 shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="font-medium text-gray-900">${supplier.name}</div>
                            <div class="text-xs text-gray-600">${supplier.contactPerson}</div>
                        </div>
                    </th>
                `;
                $tableHead.append(th);
            });

            // Defines rows structure
            const rows = [
                {
                    label: 'Overall Rating',
                    icon: '<svg class="w-5 h-5 text-yellow-500 fill-current" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" /></svg>',
                    getValue: (s) => numberFormat(s.rating, 1),
                    getSubtext: () => 'out of 5.0',
                    isBest: (s) => s.rating === stats.maxRating,
                    getValueClass: (s) => getRatingColor(s.rating),
                    bestLabel: 'Best',
                    bgBest: 'bg-green-50'
                },
                {
                    label: 'On-Time Delivery',
                    icon: '<svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>',
                    getValue: (s) => s.onTimeDelivery + '%',
                    getSubtext: () => 'reliability',
                    isBest: (s) => s.onTimeDelivery === stats.maxDelivery,
                    getValueClass: (s) => getDeliveryColor(s.onTimeDelivery),
                    bestLabel: 'Best',
                    bgBest: 'bg-green-50'
                },
                {
                    label: 'Lead Time',
                    icon: '<svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    getValue: (s) => s.leadTime,
                    getSubtext: () => 'days',
                    isBest: (s) => s.leadTime === stats.minLeadTime,
                    getValueClass: () => 'text-blue-600',
                    bestLabel: 'Fastest',
                    bgBest: 'bg-green-50'
                },
                {
                    label: 'Payment Terms',
                    icon: '<svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                    getValue: (s) => s.paymentTerms.replace('credit-', ''),
                    getSubtext: () => 'days credit',
                    isBest: () => false,
                    getValueClass: () => 'text-purple-600',
                    bgBest: ''
                },
                {
                    label: 'Credit Limit',
                    icon: '<svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
                    getValue: (s) => formatCurrency(s.creditLimit),
                    getSubtext: () => '',
                    isBest: () => false,
                    getValueClass: () => 'text-green-600',
                    bgBest: ''
                },
                {
                    label: 'Orders Completed',
                    icon: '<svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>',
                    getValue: (s) => s.totalOrders,
                    getSubtext: () => 'total orders',
                    isBest: (s) => s.totalOrders === stats.maxOrders,
                    getValueClass: () => 'text-orange-600',
                    bestLabel: 'Most Experienced',
                    bgBest: 'bg-blue-50'
                },
                {
                    label: 'Minimum Order',
                    icon: '<svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>',
                    getValue: (s) => formatCurrency(s.minimumOrder),
                    getSubtext: () => '',
                    isBest: () => false,
                    getValueClass: () => 'text-indigo-600',
                    bgBest: ''
                },
                {
                    label: 'Products Supplied',
                    icon: '<svg class="w-5 h-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>',
                    type: 'list', // Special handling
                    getValue: (s) => s.productsSupplied,
                    isBest: () => false,
                    bgBest: ''
                }
            ];

            rows.forEach(row => {
                let html = `<tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        ${row.icon}
                                        <span class="font-medium text-gray-900">${row.label}</span>
                                    </div>
                                </td>`;
                
                currentSuppliers.forEach(supplier => {
                    const isBest = row.isBest(supplier);
                    const cellBg = isBest ? row.bgBest : '';
                    
                    if (row.type === 'list') {
                        const items = row.getValue(supplier);
                        html += `
                            <td class="px-5 py-4 border-l-2 border-gray-100 align-top ${cellBg}">
                                <div class="text-center mb-2">
                                    <span class="text-xl font-bold text-teal-600">${items.length}</span>
                                    <span class="text-sm text-gray-600 ml-1">products</span>
                                </div>
                                <div class="max-h-32 overflow-y-auto text-left custom-scrollbar">
                                    <div class="space-y-1">
                                        ${items.map(item => `<div class="text-xs text-gray-600 bg-gray-50 rounded px-2 py-1">• ${item}</div>`).join('')}
                                    </div>
                                </div>
                            </td>
                        `;
                    } else {
                        html += `
                            <td class="px-5 py-4 text-center border-l-2 border-gray-100 ${cellBg}">
                                <div class="text-2xl font-bold ${row.getValueClass(supplier)}">
                                    ${row.getValue(supplier)}
                                </div>
                                ${row.getSubtext() ? `<div class="text-sm text-gray-600">${row.getSubtext()}</div>` : ''}
                                ${isBest ? `
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 mt-1">
                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138z" />
                                        </svg>
                                        ${row.bestLabel}
                                    </span>
                                ` : ''}
                            </td>
                        `;
                    }
                });

                html += `</tr>`;
                $tableBody.append(html);
            });
        }

        function renderRecommendations() {
            $recommendationsList.empty();
            const currentSuppliers = allSuppliers.filter(s => selectedSupplierIds.includes(s.id));
            if (currentSuppliers.length === 0) return;

            // Score logic: Rating(40%) + Delivery(40%) + LeadTime(20%)
            // Normalize lead time: 1/leadTime
            const ranked = [...currentSuppliers].sort((a, b) => {
                const scoreA = (a.rating * 0.4) + ((a.onTimeDelivery / 100) * 0.4) + ((1 / a.leadTime) * 0.2);
                const scoreB = (b.rating * 0.4) + ((b.onTimeDelivery / 100) * 0.4) + ((1 / b.leadTime) * 0.2);
                return scoreB - scoreA;
            });

            ranked.slice(0, 3).forEach((supplier, index) => {
                let badgeClass = 'bg-gray-500 text-white';
                let icon = '🥉';
                if (index === 0) { badgeClass = 'bg-green-500 text-white'; icon = '🏆'; }
                else if (index === 1) { badgeClass = 'bg-blue-500 text-white'; icon = '🥈'; }

                const pill = `
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-base font-medium ${badgeClass} shadow-sm animate-in fade-in zoom-in duration-300">
                        <span class="mr-2">${icon}</span> ${supplier.name}
                    </span>
                `;
                $recommendationsList.append(pill);
            });
        }

        function numberFormat(number, decimals) {
            return parseFloat(number).toFixed(decimals);
        }

        // Event Handlers
        $searchInput.on('input', renderSupplierButtons);

        $supplierList.on('click', '.supplier-btn', function() {
            const id = $(this).data('id');
            const index = selectedSupplierIds.indexOf(id);

            if (index > -1) {
                // Deselect
                selectedSupplierIds.splice(index, 1);
            } else {
                // Select
                if (selectedSupplierIds.length >= 4) {
                    toastr.warning('You can compare max 4 suppliers');
                    return;
                }
                selectedSupplierIds.push(id);
            }

            renderSupplierButtons();
            renderComparisonTable();
            renderRecommendations();
        });

        // Initial Render
        renderSupplierButtons();
        renderComparisonTable();
        renderRecommendations();
    });
</script>
@endsection