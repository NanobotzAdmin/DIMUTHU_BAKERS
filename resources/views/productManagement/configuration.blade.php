@extends('layouts.app')
@section('title', 'Product Configuration')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Product Configuration</h1>
            <p class="text-sm text-gray-500 mt-1">Manage attributes, brands, and variations for your products.</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="switchTab('product-types')" id="tab-btn-product-types"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-indigo-500 text-indigo-600">
                Product Types
            </button>
            <button onclick="switchTab('brands')" id="tab-btn-brands"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Brands
            </button>
            <button onclick="switchTab('variations')" id="tab-btn-variations"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Variations
            </button>
        </nav>
    </div>

    {{-- Tab Content: Product Types --}}
    <div id="tab-content-product-types" class="space-y-4">
        <div class="flex justify-end">
            <button onclick="openProductTypeModal('add')" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Add Product Type
            </button>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTypesTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab Content: Brands --}}
    <div id="tab-content-brands" class="space-y-4 hidden">
        <div class="flex justify-end">
            <button onclick="openBrandModal('add')" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Add Brand
            </button>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="brandsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Loaded via AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tab Content: Variations --}}
    <div id="tab-content-variations" class="grid grid-cols-1 md:grid-cols-3 gap-6 hidden">
        <!-- Variations List -->
        <div class="space-y-4 md:col-span-1">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Variations</h3>
                <button onclick="openVariationModal('add')" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition-colors">
                    Add Variation
                </button>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <ul id="variationsList" class="divide-y divide-gray-200 max-h-[600px] overflow-y-auto">
                    <!-- Loaded via AJAX -->
                </ul>
            </div>
        </div>

        <!-- Variation Values -->
        <div class="space-y-4 md:col-span-2">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Variation Values</h3>
                <button id="addVariationValueBtn" onclick="openVariationValueModal('add')" disabled class="px-3 py-1.5 bg-gray-400 text-white text-xs font-medium rounded-md transition-colors cursor-not-allowed">
                    Add Value
                </button>
            </div>
            <div id="variationValuesContainer" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center text-gray-500 min-h-[300px] flex items-center justify-center">
                Select a variation to manage its values.
            </div>
        </div>
    </div>

</div>

{{-- Modals --}}
<!-- Product Type Modal -->
<div id="productTypeModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('productTypeModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="productTypeModalTitle">Add Product Type</h3>
                <div class="mt-4">
                    <input type="hidden" id="productTypeId">
                    <label for="productTypeName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="productTypeName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2 mb-3">

                    <label for="productTypeDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="productTypeDescription" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2"></textarea>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveProductType()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                <button type="button" onclick="closeModal('productTypeModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Brand Modal -->
<div id="brandModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('brandModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="brandModalTitle">Add Brand</h3>
                <div class="mt-4 space-y-3">
                    <input type="hidden" id="brandId">
                    <div>
                        <label for="brandName" class="block text-sm font-medium text-gray-700">Brand Name</label>
                        <input type="text" id="brandName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    </div>
                    <div>
                        <label for="brandCode" class="block text-sm font-medium text-gray-700">Brand Code</label>
                        <input type="text" id="brandCode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveBrand()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                <button type="button" onclick="closeModal('brandModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Variation Modal -->
<div id="variationModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeModal('variationModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="variationModalTitle">Add Variation</h3>
                <div class="mt-4">
                    <input type="hidden" id="variationId">
                    <label for="variationName" class="block text-sm font-medium text-gray-700">Variation Name</label>
                    <input type="text" id="variationName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveVariation()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                <button type="button" onclick="closeModal('variationModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Variation Value Modal -->
<div id="variationValueModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true" onclick="closeModal('variationValueModal')"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="variationValueModalTitle">Add Value</h3>
                <div class="mt-4">
                    <input type="hidden" id="variationValueId">
                    <label for="variationValueName" class="block text-sm font-medium text-gray-700">Value</label>
                    <input type="text" id="variationValueName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                </div>
                <div class="mt-4">
                    <label for="variationValueUnit" class="block text-sm font-medium text-gray-700">Unit of Measurement</label>
                    <select id="variationValueUnit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2">
                        @foreach ($unitOfMeasurement as $id => $unit)
                        <option value="{{ $id }}">{{ $unit }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="saveVariationValue()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                <button type="button" onclick="closeModal('variationValueModal')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // --- Product Types Logic ---
    function loadProductTypes() {
        $.get('{{ route("productTypes.fetch") }}', function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<tr><td colspan="2" class="px-6 py-4 text-center text-gray-500">No product types found.</td></tr>';
            } else {
                data.forEach(item => {
                    html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.product_type_name}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">${item.description || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button onclick="openProductTypeModal('edit', ${item.id}, '${item.product_type_name}', '${(item.description || '').replace(/'/g, "\\'")}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button onclick="deleteProductType(${item.id})" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            }
            $('#productTypesTableBody').html(html);
        });
    }

    function openProductTypeModal(mode, id = null, name = '', description = '') {
        $('#productTypeModalTitle').text(mode === 'add' ? 'Add Product Type' : 'Edit Product Type');
        $('#productTypeId').val(id);
        $('#productTypeName').val(name);
        $('#productTypeDescription').val(description);
        $('#productTypeModal').removeClass('hidden');
    }

    function saveProductType() {
        const id = $('#productTypeId').val();
        const name = $('#productTypeName').val();
        const description = $('#productTypeDescription').val();
        const url = id ? '{{ route("productTypes.update") }}' : '{{ route("productTypes.store") }}';

        $.post(url, {
            id: id,
            product_type_name: name,
            description: description
        }, function(response) {
            closeModal('productTypeModal');
            loadProductTypes();
            toastr.success('Product Type saved successfully');
        }).fail(function(xhr) {
            toastr.error('Error saving product type');
        });
    }

    function deleteProductType(id) {
        if (!confirm('Are you sure?')) return;
        $.ajax({
            url: '{{ route("productTypes.delete") }}',
            type: 'DELETE',
            data: {
                id: id
            },
            success: function(response) {
                loadProductTypes();
                toastr.success('Product Type deleted');
            }
        });
    }

    // --- Brands Logic ---
    function loadBrands() {
        $.get('{{ route("brands.fetch") }}', function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No brands found.</td></tr>';
            } else {
                data.forEach(item => {
                    html += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.brand_name}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.brand_code || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <button onclick="openBrandModal('edit', ${item.id}, '${item.brand_name}', '${item.brand_code || ''}')" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button onclick="deleteBrand(${item.id})" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    `;
                });
            }
            $('#brandsTableBody').html(html);
        });
    }

    function openBrandModal(mode, id = null, name = '', code = '') {
        $('#brandModalTitle').text(mode === 'add' ? 'Add Brand' : 'Edit Brand');
        $('#brandId').val(id);
        $('#brandName').val(name);
        $('#brandCode').val(code);
        $('#brandModal').removeClass('hidden');
    }

    function saveBrand() {
        const id = $('#brandId').val();
        const name = $('#brandName').val();
        const code = $('#brandCode').val();
        const url = id ? '{{ route("brands.update") }}' : '{{ route("brands.store") }}';

        $.post(url, {
            id: id,
            brand_name: name,
            brand_code: code
        }, function(response) {
            closeModal('brandModal');
            loadBrands();
            toastr.success('Brand saved successfully');
        }).fail(function(xhr) {
            toastr.error('Error saving brand');
        });
    }

    function deleteBrand(id) {
        if (!confirm('Are you sure?')) return;
        $.ajax({
            url: '{{ route("brands.delete") }}',
            type: 'DELETE',
            data: {
                id: id
            },
            success: function(response) {
                loadBrands();
                toastr.success('Brand deleted');
            }
        });
    }

    // --- Variations Logic ---
    let currentVariationId = null;

    function loadVariations() {
        $.get('{{ route("variations.fetch") }}', function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<li class="px-4 py-4 text-center text-gray-500 text-sm">No variations found.</li>';
            } else {
                data.forEach(item => {
                    html += `
                        <li class="flex items-center justify-between p-4 hover:bg-gray-50 cursor-pointer ${currentVariationId === item.id ? 'bg-indigo-50 border-l-4 border-indigo-500' : ''}" onclick="selectVariation(${item.id}, this)">
                            <span class="text-sm font-medium text-gray-900">${item.variation_name}</span>
                            <div class="flex items-center space-x-2">
                                <button onclick="event.stopPropagation(); openVariationModal('edit', ${item.id}, '${item.variation_name}')" class="text-xs text-indigo-600 hover:text-indigo-900">Edit</button>
                                <button onclick="event.stopPropagation(); deleteVariation(${item.id})" class="text-xs text-red-600 hover:text-red-900">Del</button>
                            </div>
                        </li>
                    `;
                });
            }
            $('#variationsList').html(html);
        });
    }

    function selectVariation(id, element) {
        currentVariationId = id;
        $('#variationsList li').removeClass('bg-indigo-50 border-l-4 border-indigo-500');
        $(element).addClass('bg-indigo-50 border-l-4 border-indigo-500');

        $('#addVariationValueBtn').prop('disabled', false).removeClass('bg-gray-400 cursor-not-allowed').addClass('bg-indigo-600 hover:bg-indigo-700');
        loadVariationValues(id);
    }

    function loadVariationValues(variationId) {
        $.get('{{ route("variationValues.fetch") }}', {
            variation_id: variationId
        }, function(response) {
            // response has structure { values: [...], unitOfMeasurement: {...} }
            const data = response.values;
            const units = response.unitOfMeasurement;

            if (data.length === 0) {
                $('#variationValuesContainer').html(`
                    <div class="text-center text-gray-500 py-10">
                        <p class="mb-2">No values found for this variation.</p>
                        <button onclick="openVariationValueModal('add')" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Add a value</button>
                    </div>
                `);
            } else {
                let html = '<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">';
                data.forEach(item => {
                    const unitName = units[item.unit_of_measurement_id] || '';
                    html += `
                        <div class="relative group bg-gray-50 rounded-lg p-3 border border-gray-200 hover:border-indigo-300 transition-colors">
                            <div class="text-center font-medium text-gray-900">${item.variation_value} <span class="text-xs text-gray-500">${unitName}</span></div>
                            <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-1">
                                <button onclick="openVariationValueModal('edit', ${item.id}, '${item.variation_value}', '${item.unit_of_measurement_id}')" class="p-1 text-indigo-600 hover:bg-indigo-50 rounded"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button onclick="deleteVariationValue(${item.id})" class="p-1 text-red-600 hover:bg-red-50 rounded"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                $('#variationValuesContainer').html(html);
            }
        });
    }

    function openVariationModal(mode, id = null, name = '') {
        $('#variationModalTitle').text(mode === 'add' ? 'Add Variation' : 'Edit Variation');
        $('#variationId').val(id);
        $('#variationName').val(name);
        $('#variationModal').removeClass('hidden');
    }

    function saveVariation() {
        const id = $('#variationId').val();
        const name = $('#variationName').val();
        const url = id ? '{{ route("variations.update") }}' : '{{ route("variations.store") }}';

        $.post(url, {
            id: id,
            variation_name: name
        }, function(response) {
            closeModal('variationModal');
            loadVariations();
            toastr.success('Variation saved successfully');
        }).fail(function(xhr) {
            toastr.error('Error saving variation');
        });
    }

    function deleteVariation(id) {
        if (!confirm('Deleting this variation will delete all its values. Are you sure?')) return;
        $.ajax({
            url: '{{ route("variations.delete") }}',
            type: 'DELETE',
            data: {
                id: id
            },
            success: function(response) {
                loadVariations();
                if (currentVariationId === id) {
                    currentVariationId = null;
                    $('#variationValuesContainer').html('Select a variation to manage its values.');
                    $('#addVariationValueBtn').prop('disabled', true).addClass('bg-gray-400 cursor-not-allowed').removeClass('bg-indigo-600 hover:bg-indigo-700');
                }
                toastr.success('Variation deleted');
            }
        });
    }

    function openVariationValueModal(mode, id = null, value = '', unitId = '') {
        $('#variationValueModalTitle').text(mode === 'add' ? 'Add Value' : 'Edit Value');
        $('#variationValueId').val(id);
        $('#variationValueName').val(value);
        if (unitId) {
            $('#variationValueUnit').val(unitId);
        } else {
            // Reset to first option if adding new or no unit provided
            $('#variationValueUnit').prop('selectedIndex', 0);
        }

        $('#variationValueModal').removeClass('hidden');
    }

    function saveVariationValue() {
        const id = $('#variationValueId').val();
        const value = $('#variationValueName').val();
        const unit_of_measurement_id = $('#variationValueUnit').val();
        const url = id ? '{{ route("variationValues.update") }}' : '{{ route("variationValues.store") }}';
        const data = {
            id: id,
            variation_value: value,
            variation_id: currentVariationId,
            unit_of_measurement_id: unit_of_measurement_id
        };

        $.post(url, data, function(response) {
            closeModal('variationValueModal');
            loadVariationValues(currentVariationId);
            toastr.success('Value saved successfully');
        }).fail(function(xhr) {
            toastr.error('Error saving value');
        });
    }

    function deleteVariationValue(id) {
        if (!confirm('Are you sure?')) return;
        $.ajax({
            url: '{{ route("variationValues.delete") }}',
            type: 'DELETE',
            data: {
                id: id
            },
            success: function(response) {
                loadVariationValues(currentVariationId);
                toastr.success('Value deleted');
            }
        });
    }

    function closeModal(id) {
        $('#' + id).addClass('hidden');
    }

    // --- Tab Switching Logic ---
    function switchTab(tabName) {
        // Hide all tab contents
        $('#tab-content-product-types, #tab-content-brands, #tab-content-variations').addClass('hidden');
        // Show selected tab content
        $('#tab-content-' + tabName).removeClass('hidden');

        // Reset all tab buttons
        const inactiveClass = 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300';
        const activeClass = 'border-indigo-500 text-indigo-600';

        $('#tab-btn-product-types, #tab-btn-brands, #tab-btn-variations')
            .removeClass(activeClass)
            .addClass(inactiveClass);

        // Highlight selected tab button
        $('#tab-btn-' + tabName)
            .removeClass(inactiveClass)
            .addClass(activeClass);

        // Load data for the selected tab
        if (tabName === 'product-types') loadProductTypes();
        else if (tabName === 'brands') loadBrands();
        else if (tabName === 'variations') loadVariations();
    }

    // Initial load based on default active tab
    $(document).ready(function() {
        // Load default tab
        switchTab('product-types');
    });
</script>
@endsection