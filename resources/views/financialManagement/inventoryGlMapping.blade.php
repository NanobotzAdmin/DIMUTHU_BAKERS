@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-2">
                <i class="bi bi-box-seam text-4xl text-amber-500"></i>
                <h1 class="text-3xl font-bold text-gray-900">Inventory & Procurement GL Mapping</h1>
            </div>
            <p class="text-gray-600">Configure GL account mappings for inventory categories and supplier types</p>
        </div>

        <div class="max-w-[1400px] mx-auto space-y-6">
            <!-- Tabs -->
            <div class="flex gap-2 border-b border-gray-200" id="gl-tabs">
                <button onclick="glMappingManager.switchTab('categories')" id="tab-btn-categories"
                    class="px-4 py-2 border-b-2 transition-colors font-medium border-amber-500 text-amber-500 flex items-center gap-2">
                    <i class="bi bi-box-seam"></i> Inventory Categories
                </button>
                <button onclick="glMappingManager.switchTab('suppliers')" id="tab-btn-suppliers"
                    class="px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="bi bi-truck"></i> Supplier Types
                </button>
                <button onclick="glMappingManager.switchTab('config')" id="tab-btn-config"
                    class="px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 flex items-center gap-2">
                    <i class="bi bi-gear"></i> Valuation Configuration
                </button>
            </div>

            <!-- Categories Tab -->
            <div id="tab-content-categories" class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Inventory Category GL Mappings</h2>
                    <p class="text-sm text-gray-500">Map inventory categories to GL accounts for inventory and cost of goods
                        sold</p>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b text-sm text-gray-600 bg-gray-50">
                                    <th class="text-left py-3 px-4 rounded-tl-lg">Category</th>
                                    <th class="text-left py-3 px-4">Type</th>
                                    <th class="text-left py-3 px-4 w-1/4">Inventory GL Account</th>
                                    <th class="text-left py-3 px-4 w-1/4">COGS GL Account</th>
                                    <th class="text-center py-3 px-4">Status</th>
                                    <th class="text-left py-3 px-4 rounded-tr-lg">Notes</th>
                                </tr>
                            </thead>
                            <tbody id="categories-table-body">
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-900">
                        <p class="font-bold mb-2 flex items-center gap-2"><i class="bi bi-info-circle-fill"></i> How
                            Category Mappings Work:</p>
                        <ul class="space-y-1 ml-4 list-disc marker:text-blue-500">
                            <li><strong>Inventory GL Account:</strong> Debit when goods received, Credit when goods
                                sold/used</li>
                            <li><strong>COGS GL Account:</strong> Debit when goods sold (transfers cost from inventory to
                                expense)</li>
                            <li><strong>Raw Materials:</strong> Used for ingredients purchased for production</li>
                            <li><strong>Finished Goods:</strong> Completed products ready for sale</li>
                            <li><strong>Resale Items:</strong> Products purchased for resale without modification</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Suppliers Tab -->
            <div id="tab-content-suppliers" class="bg-white rounded-xl shadow-sm border border-gray-200 hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900">Supplier Type GL Mappings</h2>
                    <p class="text-sm text-gray-500">Map supplier types to GL accounts for accounts payable and default
                        expenses</p>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b text-sm text-gray-600 bg-gray-50">
                                    <th class="text-left py-3 px-4 rounded-tl-lg">Supplier Type</th>
                                    <th class="text-left py-3 px-4 w-1/4">Accounts Payable Account</th>
                                    <th class="text-left py-3 px-4 w-1/4">Default Expense Account</th>
                                    <th class="text-center py-3 px-4">Status</th>
                                    <th class="text-left py-3 px-4 rounded-tr-lg">Notes</th>
                                </tr>
                            </thead>
                            <tbody id="suppliers-table-body">
                                <!-- Dynamic Rows -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-900">
                        <p class="font-bold mb-2 flex items-center gap-2"><i class="bi bi-info-circle-fill"></i> How
                            Supplier Mappings Work:</p>
                        <ul class="space-y-1 ml-4 list-disc marker:text-blue-500">
                            <li><strong>Accounts Payable Account:</strong> Credit when invoice received, Debit when payment
                                made</li>
                            <li><strong>Default Expense Account:</strong> Used when creating purchase orders/invoices for
                                this supplier type</li>
                            <li><strong>Ingredient Suppliers:</strong> Suppliers of flour, sugar, butter, etc.</li>
                            <li><strong>Packaging Suppliers:</strong> Suppliers of boxes, bags, labels</li>
                            <li><strong>Utility Providers:</strong> Electricity, water, gas companies</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Valuation Config Tab -->
            <div id="tab-content-config" class="space-y-6 hidden">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">Inventory Valuation Method</h2>
                        <p class="text-sm text-gray-500">Select the inventory valuation method for cost calculation</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="valuation-methods-grid">
                            <!-- Methods -->
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4 text-sm border border-gray-100">
                            <p class="font-medium mb-1">Current Method: <span id="current-method-name"
                                    class="text-amber-600"></span></p>
                            <p class="text-gray-500">
                                Last updated: <span id="config-updated-at"></span> by <span id="config-updated-by"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">Advanced Inventory Features</h2>
                        <p class="text-sm text-gray-500">Enable additional inventory management and costing features</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Toggles -->
                        <div class="space-y-4" id="advanced-features-list">
                            <!-- Dynamic Toggles -->
                        </div>
                    </div>
                </div>

                <!-- Config Summary -->
                <div class="bg-white rounded-xl shadow-sm border-2 border-amber-400 p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-900">Configuration Summary</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" id="config-summary-grid">
                        <!-- Dynamic Summary -->
                    </div>
                </div>
            </div>

            <!-- Help Footer -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                <h3 class="font-bold text-lg text-blue-900 mb-3">GL Mapping Best Practices</h3>
                <div class="text-sm text-blue-900 space-y-3">
                    <div>
                        <p><strong>Inventory Categories:</strong></p>
                        <ul class="ml-4 mt-1 space-y-1 list-disc marker:text-blue-500">
                            <li>Separate GL accounts for each major inventory type (Raw Materials, Packaging, Finished
                                Goods)</li>
                            <li>Use consistent COGS accounts that match your income statement structure</li>
                            <li>Consider sub-accounts if you need detailed tracking by location or category</li>
                        </ul>
                    </div>
                    <div>
                        <p><strong>Supplier Types:</strong></p>
                        <ul class="ml-4 mt-1 space-y-1 list-disc marker:text-blue-500">
                            <li>Separate AP accounts help track obligations by supplier category</li>
                            <li>Default expense accounts speed up data entry for recurring purchases</li>
                            <li>Use different AP accounts if you need aging reports by supplier type</li>
                        </ul>
                    </div>
                    <div>
                        <p><strong>Valuation Methods:</strong></p>
                        <ul class="ml-4 mt-1 space-y-1 list-disc marker:text-blue-500">
                            <li><strong>FIFO:</strong> Best for perishable goods, matches physical flow</li>
                            <li><strong>Weighted Average:</strong> Simplest, good for bakery with frequent purchases</li>
                            <li><strong>Standard Cost:</strong> Best for variance analysis, requires maintenance</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const glMappingManager = {
            data: {
                categoryMappings: @json($categoryMappings),
                supplierMappings: @json($supplierMappings),
                valuationConfig: @json($valuationConfig),
                glAccounts: @json($glAccounts)
            },

            methods: [
                { value: 'fifo', label: 'FIFO', description: 'First In, First Out - Oldest costs used first' },
                { value: 'weighted-average', label: 'Weighted Average', description: 'Average cost of all units in inventory' },
                { value: 'standard-cost', label: 'Standard Cost', description: 'Predetermined standard costs with variance tracking' }
            ],

            init() {
                this.renderCategories();
                this.renderSuppliers();
                this.renderConfig();
            },

            switchTab(tabId) {
                // UI Tab Update
                const tabs = ['categories', 'suppliers', 'config'];
                tabs.forEach(t => {
                    const btn = document.getElementById(`tab-btn-${t}`);
                    const content = document.getElementById(`tab-content-${t}`);

                    if (t === tabId) {
                        btn.className = "px-4 py-2 border-b-2 transition-colors font-medium border-amber-500 text-amber-500 flex items-center gap-2";
                        content.classList.remove('hidden');
                    } else {
                        btn.className = "px-4 py-2 border-b-2 transition-colors border-transparent text-gray-600 hover:text-gray-900 flex items-center gap-2";
                        content.classList.add('hidden');
                    }
                });
            },

            renderCategories() {
                const tbody = document.getElementById('categories-table-body');
                const inventoryAccs = this.data.glAccounts.filter(a => a.category === 'Inventory');
                const cogsAccs = this.data.glAccounts.filter(a => a.category === 'COGS');

                tbody.innerHTML = this.data.categoryMappings.map(map => `
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 font-medium text-gray-900">${map.categoryName}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded text-xs border border-gray-200 text-gray-600 bg-white">${map.categoryType}</span>
                                </td>
                                <td class="py-3 px-4">
                                    <select onchange="glMappingManager.updateCategoryMapping('${map.id}', 'inventoryAccountId', this.value)"
                                        class="w-full p-2 bg-white text-sm border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                                        ${inventoryAccs.map(acc => `<option value="${acc.id}" ${acc.id == map.inventoryAccountId ? 'selected' : ''}>${acc.code} - ${acc.name}</option>`).join('')}
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                     <select onchange="glMappingManager.updateCategoryMapping('${map.id}', 'cogsAccountId', this.value)"
                                        class="w-full p-2 bg-white text-sm border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                                        ${cogsAccs.map(acc => `<option value="${acc.id}" ${acc.id == map.cogsAccountId ? 'selected' : ''}>${acc.code} - ${acc.name}</option>`).join('')}
                                    </select>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    ${map.isActive
                        ? '<span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium border border-green-200 flex items-center justify-center gap-1 w-fit mx-auto"><i class="bi bi-check-circle-fill"></i> Active</span>'
                        : '<span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium border border-gray-200 w-fit mx-auto">Inactive</span>'}
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-500">${map.notes}</td>
                            </tr>
                        `).join('');
            },

            renderSuppliers() {
                const tbody = document.getElementById('suppliers-table-body');
                const payablesAccs = this.data.glAccounts.filter(a => a.category === 'Payables');
                const expenseAccs = this.data.glAccounts.filter(a => a.type === 'Expense');

                tbody.innerHTML = this.data.supplierMappings.map(map => `
                            <tr class="border-b hover:bg-gray-50 transition-colors">
                                 <td class="py-3 px-4 font-medium text-gray-900">${map.supplierTypeName}</td>
                                 <td class="py-3 px-4">
                                    <select onchange="glMappingManager.updateSupplierMapping('${map.id}', 'apAccountId', this.value)"
                                        class="w-full p-2 bg-white text-sm border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                                        ${payablesAccs.map(acc => `<option value="${acc.id}" ${acc.id == map.apAccountId ? 'selected' : ''}>${acc.code} - ${acc.name}</option>`).join('')}
                                    </select>
                                </td>
                                <td class="py-3 px-4">
                                     <select onchange="glMappingManager.updateSupplierMapping('${map.id}', 'defaultExpenseAccountId', this.value)"
                                        class="w-full p-2 bg-white text-sm border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm">
                                        <option value="">Select Account...</option>
                                        ${expenseAccs.map(acc => `<option value="${acc.id}" ${acc.id == map.defaultExpenseAccountId ? 'selected' : ''}>${acc.code} - ${acc.name}</option>`).join('')}
                                    </select>
                                </td>
                                 <td class="py-3 px-4 text-center">
                                    ${map.isActive
                        ? '<span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium border border-green-200 flex items-center justify-center gap-1 w-fit mx-auto"><i class="bi bi-check-circle-fill"></i> Active</span>'
                        : '<span class="px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium border border-gray-200 w-fit mx-auto">Inactive</span>'}
                                </td>
                                 <td class="py-3 px-4 text-sm text-gray-500">${map.notes}</td>
                            </tr>
                        `).join('');
            },

            renderConfig() {
                // Valuation Methods Grid
                const grid = document.getElementById('valuation-methods-grid');
                grid.innerHTML = this.methods.map(m => {
                    const isSelected = this.data.valuationConfig.method === m.value;
                    return `
                                <div onclick="glMappingManager.updateConfig('method', '${m.value}')"
                                    class="border-2 rounded-xl p-4 cursor-pointer transition-all ${isSelected ? 'border-amber-400 bg-amber-50 shadow-sm' : 'border-gray-200 hover:border-gray-300 hover:shadow-sm'}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center ${isSelected ? 'border-amber-500' : 'border-gray-300'}">
                                             ${isSelected ? '<div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>' : ''}
                                        </div>
                                        <span class="font-bold text-gray-900">${m.label}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 pl-7">${m.description}</p>
                                </div>
                            `;
                }).join('');

                // Config Details
                document.getElementById('current-method-name').innerText = this.data.valuationConfig.methodName;
                document.getElementById('config-updated-at').innerText = this.data.valuationConfig.updatedAt;
                document.getElementById('config-updated-by').innerText = this.data.valuationConfig.updatedBy;

                // Toggles
                const features = [
                    { key: 'enableNRVAdjustments', label: 'Net Realizable Value (NRV) Adjustments', desc: 'Automatically write down inventory to NRV when market value drops below cost' },
                    { key: 'enablePriceVarianceTracking', label: 'Purchase Price Variance Tracking', desc: 'Track variance between PO price and actual invoice price' },
                    { key: 'enableLandedCost', label: 'Landed Cost Allocation', desc: 'Include freight, insurance, and customs in inventory cost' },
                    { key: 'autoPostInventoryJEs', label: 'Auto-Post Inventory Journal Entries', desc: 'Automatically post inventory JEs instead of creating drafts' }
                ];

                const list = document.getElementById('advanced-features-list');
                list.innerHTML = features.map(f => `
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">${f.label}</h4>
                                    <p class="text-sm text-gray-500 mt-1">${f.desc}</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer ml-4">
                                    <input type="checkbox" onchange="glMappingManager.updateConfig('${f.key}', this.checked)"
                                        class="sr-only peer" ${this.data.valuationConfig[f.key] ? 'checked' : ''}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                                </label>
                            </div>
                        `).join('');

                // Summary
                const summary = document.getElementById('config-summary-grid');
                summary.innerHTML = `
                             <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-600">Valuation Method:</span> <span class="ml-2 font-medium text-gray-900">${this.data.valuationConfig.methodName}</span></div>
                             <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-600">NRV Adjustments:</span> <span class="ml-2 font-medium text-gray-900">${this.data.valuationConfig.enableNRVAdjustments ? 'Enabled' : 'Disabled'}</span></div>
                             <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-600">Price Variance Tracking:</span> <span class="ml-2 font-medium text-gray-900">${this.data.valuationConfig.enablePriceVarianceTracking ? 'Enabled' : 'Disabled'}</span></div>
                             <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-600">Landed Cost:</span> <span class="ml-2 font-medium text-gray-900">${this.data.valuationConfig.enableLandedCost ? 'Enabled' : 'Disabled'}</span></div>
                             <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-600">Auto-Post JEs:</span> <span class="ml-2 font-medium text-gray-900">${this.data.valuationConfig.autoPostInventoryJEs ? 'Yes' : 'No'}</span></div>
                             <div class="bg-gray-50 rounded-lg p-3"><span class="text-gray-600">Last Updated:</span> <span class="ml-2 font-medium text-gray-900">${this.data.valuationConfig.updatedAt}</span></div>
                        `;
            },

            // --- Actions ---

            updateCategoryMapping(id, field, value) {
                // Mock Update
                const mapping = this.data.categoryMappings.find(m => m.id === id);
                if (mapping) {
                    mapping[field] = value;
                    Swal.fire({
                        icon: 'success',
                        title: 'Mapping Updated',
                        text: 'Category GL mapping has been updated successfully.',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            },

            updateSupplierMapping(id, field, value) {
                // Mock Update
                const mapping = this.data.supplierMappings.find(m => m.id === id);
                if (mapping) {
                    mapping[field] = value;
                    Swal.fire({
                        icon: 'success',
                        title: 'Mapping Updated',
                        text: 'Supplier mapping updated successfully.',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            },

            updateConfig(key, value) {
                // Mock Update
                this.data.valuationConfig[key] = value;

                if (key === 'method') {
                    const m = this.methods.find(x => x.value === value);
                    this.data.valuationConfig.methodName = m ? m.label : value;
                }

                // Update timestamps
                this.data.valuationConfig.updatedAt = new Date().toISOString().replace('T', ' ').substring(0, 19);
                this.data.valuationConfig.updatedBy = 'Current User';

                this.renderConfig(); // Re-render to update UI state

                Swal.fire({
                    icon: 'success',
                    title: 'Configuration Saved',
                    text: 'Inventory valuation settings updated.',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            glMappingManager.init();
        });
    </script>
@endsection