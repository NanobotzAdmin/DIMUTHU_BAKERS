@extends('layouts.app')
@section('title', 'Product Registration')

@section('content')
    <style>

    </style>
    <div class="max-w-7xl mx-auto py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <i class="bi bi-box-seam text-blue-600 text-4xl "></i>
                Product Registration
            </h1>
            <p class="text-gray-600 mt-2">Add a new product to the master database.</p>
        </div>

        <form action="{{ route('product.store') }}" method="POST" id="productForm">

            @csrf

            <div class="space-y-8 mb-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                        <div class="flex items-center gap-4 bg-gray-100 p-1.5 rounded-lg">
                            <label
                                class="relative flex items-center justify-center px-4 py-2 rounded-md cursor-pointer transition-all">
                                <input type="radio" name="product_mode" value="standard" checked class="peer sr-only"
                                    onchange="toggleProductMode('standard')">
                                <span class="z-10 text-sm font-medium text-gray-600 peer-checked:text-blue-600">Standard
                                    Product</span>
                                <span
                                    class="absolute inset-0 bg-white shadow-sm rounded-md opacity-0 peer-checked:opacity-100 transition-all"></span>
                            </label>
                            <label
                                class="relative flex items-center justify-center px-4 py-2 rounded-md cursor-pointer transition-all">
                                <input type="radio" name="product_mode" value="simple" class="peer sr-only"
                                    onchange="toggleProductMode('simple')" disabled>
                                <span class="z-10 text-sm font-medium text-gray-600 peer-checked:text-blue-600">Simple
                                    Product</span>
                                <span
                                    class="absolute inset-0 bg-white shadow-sm rounded-md opacity-0 peer-checked:opacity-100 transition-all"></span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" id="productNameInput" value="{{ old('name') }}"
                                autocomplete="off" placeholder="e.g., All-Purpose Flour, Milo Milk Packets"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                            <!-- Autocomplete Dropdown -->
                            <div id="productSuggestions"
                                class="absolute z-10 w-full bg-white border border-gray-200 rounded-xl shadow-lg max-h-60 overflow-y-auto hidden">
                                <ul class="py-1 text-sm text-gray-700" id="suggestionList"></ul>
                            </div>
                            <input type="hidden" id="existingProductId" name="existing_product_id">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" id="productDescription" rows="2" placeholder="Optional product description"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">{{ old('description') }}</textarea>
                        </div>

                        <!-- <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                                                                <input type="text" name="category" id="categoryInput" value="{{ old('category') }}"
                                                                    placeholder="e.g., flour, dairy, sugar"
                                                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                                                                @error('category')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit of Measurement <span class="text-red-500">*</span></label>
                                                                <select name="brand" id="brandSelect" onchange="checkExistingItems()" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors bg-white">
                                                                    </select>
                                                                @error('unit')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
                                                            </div> -->

                        <div class="md:col-span-2" id="brandField">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                            <select name="brand" id="brandSelect"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors bg-white select2-enable">
                                <option value="">Select Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand['id'] }}">{{ $brand['brand_name'] }}</option>
                                @endforeach
                            </select>
                            @error('brand')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200 mb-6" id="variantSettingsSection">
                <h4 class="font-semibold text-gray-900 mb-4">Variant Settings</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4">
                    <div>
                        <div class="flex items-center justify-start mb-2">
                            <label class="block text-sm font-medium text-gray-700">Variant Name </label>
                            <button type="button" onclick="openAddVariantModal()"
                                class="text-blue-500 hover:text-blue-600 cursor-pointer"><i
                                    class="bi bi-plus-circle mr-1 ml-2"></i>Add New Variant</button>
                        </div>
                        <select name="variant_name" id="variantSelect"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors select2-enable">
                            <option value="">Select Variant Name</option>
                            @foreach ($variants as $variant)
                                <option value="{{ $variant['id'] }}">{{ $variant['variation_name'] }}</option>
                            @endforeach
                        </select>
                        @error('variant_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="flex items-center justify-start mb-2">
                            <label class="block text-sm font-medium text-gray-700">Variant Value </label>
                            <button type="button" onclick="openAddVariantValueModal()"
                                class="text-blue-500 hover:text-blue-600 cursor-pointer"><i
                                    class="bi bi-plus-circle mr-1 ml-2"></i>Add New Variant Value</button>
                        </div>
                        <select name="variant_value" id="variantValueSelect" multiple
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors multiple select2-enable">
                            <option value="">Select Variant Value</option>
                        </select>
                        @error('variant_value')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="button" id="addVariantButton" onclick="addVariant()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-xl transition-colors">Add
                            Variant</button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Variant Name
                                        </th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Variant Values
                                        </th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">
                                            Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200" id="variantSettingsBody">
                                    <!-- Dynamic Rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="flex items-end justify-end mt-4 gap-3 w-full">
                    <button type="button" id="generateProductBtn" onclick="generateProducts()" disabled
                        class="bg-blue-500 hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-2 rounded-xl transition-colors">Generate
                        Products</button>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-6 border-2 border-gray-200">
                <h4 class="font-semibold text-gray-900 mb-4">Product Items</h4>

                <div class="grid grid-cols-1 gap-6">
                    <div class="overflow-hidden bg-white rounded-xl border border-gray-200 shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-500">
                                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">#</th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Product Name
                                        </th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Reference Number
                                        </th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Product Types
                                        </th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Selling Price
                                        </th>
                                        <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">
                                            Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200" id="productItemsBody">
                                    <!-- Dynamic Product Items -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <div class="flex gap-4 pt-6 border-t border-gray-200">
                <a href="{{ url()->previous() }}"
                    class="flex-1 h-14 flex items-center justify-center bg-gray-100 hover:bg-red-200 hover:text-red-900 text-gray-700 rounded-xl font-medium transition-all gap-2 ">
                    <i class="bi bi-x-circle text-2xl"></i>
                    Cancel
                </a>
                <button type="button" id="createProductBtn" onclick="submitProductForm()" disabled
                    class="flex-1 h-14 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-500 disabled:cursor-not-allowed text-white rounded-xl font-medium shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="bi bi-check2-circle text-2xl"></i>
                    Create Product
                </button>
            </div>

    </div>
    </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {



            // Initialize Select2
            $('.select2-enable').select2({
                width: '100%' // Ensure it takes full width of container
            });

            // Bind events using jQuery for Select2 compatibility
            $('#brandSelect').on('change', function() {
                checkExistingItems();
            });

            $('#variantSelect').on('change', function() {
                loadVariationValues(this.value);
            });
        });



        const productTypesHtml =
            `@foreach ($productTypes as $type)<option value="{{ $type['id'] }}">{{ $type['product_type_name'] }}</option>@endforeach`;

        function toggleProductMode(mode) {
            const brandField = document.getElementById('brandField');
            const variantSection = document.getElementById('variantSettingsSection');
            const generateBtn = document.getElementById('generateProductBtn');
            const tbody = document.getElementById('productItemsBody');

            if (mode === 'simple') {
                brandField.classList.add('hidden');
                variantSection.classList.add('hidden');

                // Clear standard generated items
                addedVariants = [];
                renderVariantTable();
                checkGenerateButton();

                // Auto-generate single item row for Simple product
                generateSimpleProductItem();
            } else {
                brandField.classList.remove('hidden');
                variantSection.classList.remove('hidden');

                // Clear simple item if exists
                tbody.innerHTML = '';
                checkCreateButton();
            }
        }

        function generateSimpleProductItem() {
            const productName = document.getElementById('productNameInput').value;
            const tbody = document.getElementById('productItemsBody');

            tbody.innerHTML = ''; // Clear previous

            // Only proceed to create the row if productName has at least one character
            if (productName.length === 0) {
                checkCreateButton(); // Ensure create button is disabled if no row is created
                return;
            }

            const tr = document.createElement('tr');
            tr.className = "hover:bg-gray-50 transition-colors";

            tr.innerHTML = '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">1</td>' +
                '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' +
                productName +
                '<input type="hidden" name="items[1][name]" value="' + productName + '">' +
                '<!-- No generic variant IDs for simple products -->' +
                '</td>' +
                '<td class="px-6 py-4">' +
                '<input type="text" name="items[1][reference_number]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" placeholder="Reference Number">' +
                '</td>' +
                '<td class="px-6 py-4">' +
                '<select name="items[1][product_types][]" multiple class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 select2-enable">' +
                productTypesHtml +
                '</select>' +
                '</td>' +
                '<td class="px-6 py-4">' +
                '<input type="number" step="0.01" name="items[1][selling_price]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" placeholder="0.00">' +
                '</td>' +
                '<td class="px-6 py-4 text-right whitespace-nowrap">' +
                '<!-- Cannot remove the only item in simple mode? Or maybe just clear? -->' +
                '</td>';
            tbody.appendChild(tr);

            // Initialize Select2 for the newly added product types select
            const newSelect = tr.querySelector('select[name^="items"][name$="[product_types][]"]');
            if (newSelect) {
                $(newSelect).select2();
            }

            checkCreateButton();
        }

        // Attach listener to name input to update simple item name live
        document.getElementById('productNameInput').addEventListener('input', function() {
            const mode = document.querySelector('input[name="product_mode"]:checked').value;
            if (mode === 'simple') {
                generateSimpleProductItem();
            }
        });

        // --- Variant & Product Item Logic ---

        let addedVariants = []; // Stores { id, name, values: [{id, name, unit}] }
        let existingProductVariations = []; // Stores { pm_variation_id, pm_variation_value_id } for existing items

        function loadVariationValues(variationId) {
            const valueSelect = document.getElementById('variantValueSelect');
            valueSelect.innerHTML = '';

            if (!variationId) return;

            fetch(`{{ route('variationValues.fetch') }}?variation_id=${variationId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.values && data.values.length > 0) {
                        data.values.forEach(val => {
                            const option = document.createElement('option');
                            option.value = val.id;

                            // Map unit ID to name
                            const unitName = (data.unitOfMeasurement && data.unitOfMeasurement[val
                                .unit_of_measurement_id]) ? data.unitOfMeasurement[val
                                .unit_of_measurement_id] : '';
                            option.text = val.variation_value + (unitName ? ' ' + unitName : '');

                            // Store full object in dataset for easier access if needed
                            option.dataset.name = option.text;
                            valueSelect.appendChild(option);
                        });

                        // Trigger change to update Select2
                        $('#variantValueSelect').trigger('change');
                    }
                })
                .catch(error => console.error('Error loading values:', error));
        }

        function addVariant() {
            const variantSelect = document.getElementById('variantSelect');
            const valueSelect = document.getElementById('variantValueSelect');

            const variantId = variantSelect.value;
            const variantName = variantSelect.options[variantSelect.selectedIndex].text;
            const selectedOptions = Array.from(valueSelect.selectedOptions).filter(opt => opt.value !== "");

            if (!variantId || selectedOptions.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select a variant and at least one value.'
                });
                return;
            }

            // Filter out options that are already present, and collect the ones to add
            const valuesToAdd = [];
            for (const opt of selectedOptions) {
                const valueId = opt.value;
                const valueName = opt.text;

                // Check if it's already saved in the DB
                const isSaved = existingProductVariations.some(v => v.pm_variation_id == variantId && v
                    .pm_variation_value_id == valueId);
                if (isSaved) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Already Exists',
                        text: `The variant "${valueName}" is already saved for this product.`
                    });
                    continue; // Skip this one
                }

                // Check if it's already been added to the UI list
                const isAddedInUI = addedVariants.some(v => v.id === variantId && v.values.some(val => val.id ===
                    valueId));
                if (isAddedInUI) {
                    // Silently skip, or show a message. Let's just skip it.
                    continue;
                }

                valuesToAdd.push({
                    id: valueId,
                    name: valueName
                });
            }

            if (valuesToAdd.length === 0) {
                // This means all selected items were duplicates
                $('#variantValueSelect').val(null).trigger('change'); // Clear selection
                return;
            }

            // Now, add the new, valid values
            let existingVariantGroup = addedVariants.find(v => v.id === variantId);

            if (existingVariantGroup) {
                existingVariantGroup.values.push(...valuesToAdd);
            } else {
                addedVariants.push({
                    id: variantId,
                    name: variantName,
                    values: valuesToAdd
                });
            }

            renderVariantTable();
            checkGenerateButton();

            // Reset Selection
            $('#variantValueSelect').val(null).trigger('change');
        }

        function removeVariant(variantId) {
            addedVariants = addedVariants.filter(v => v.id !== variantId);
            renderVariantTable();
            checkGenerateButton();
        }

        function renderVariantTable() {
            const tbody = document.getElementById('variantSettingsBody');
            tbody.innerHTML = '';

            addedVariants.forEach(variant => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50 transition-colors";

                let valuesHtml = variant.values.map(val =>
                    `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">${val.name}</span>`
                ).join(' ');

                tr.innerHTML = `
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">${variant.name}</td>
                                    <td class="px-6 py-4"><div class="flex flex-wrap gap-2">${valuesHtml}</div></td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <button type="button" onclick="removeVariant('${variant.id}')" class="group inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 transition-all hover:bg-red-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            Remove
                                        </button>
                                        <!-- Hidden inputs to submit selected variants structure if needed, but we mostly need items -->
                                    </td>
                                `;
                tbody.appendChild(tr);
            });
        }

        function checkGenerateButton() {
            const btn = document.getElementById('generateProductBtn');
            btn.disabled = addedVariants.length === 0;
        }

        function generateProducts() {
            const productName = document.querySelector('input[name="name"]').value;
            const brandSelect = document.getElementById('brandSelect'); // Optional
            const brandName = brandSelect && brandSelect.value ? brandSelect.options[brandSelect.selectedIndex].text : '';

            if (!productName) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please enter a Product Name first.'
                });
                return;
            }

            const tbody = document.getElementById('productItemsBody');

            // Clear only non-existing product item rows
            Array.from(tbody.children).forEach(row => {
                if (!row.classList.contains('existing-item-row')) {
                    row.remove();
                }
            });

            let count = 1;

            // Generate logic: One item per Variation Value

            addedVariants.forEach(variant => {

                variant.values.forEach(val => {

                    const tr = document.createElement('tr');

                    tr.className = "hover:bg-gray-50 transition-colors";



                    const brandSelect = document.getElementById('brandSelect');

                    const brandName = brandSelect && brandSelect.value ? brandSelect.options[brandSelect

                        .selectedIndex].text : '';



                    // Format: "Brand Name Product Name - Variant Value"

                    const variantPart = `- ${val.name}`;

                    let brandPart = '';

                    if (brandName) {

                        brandPart = `${brandName} `;

                    }

                    tr.dataset.brandPart = brandPart;

                    tr.dataset.variantPart = variantPart;



                    let fullName = `${brandPart}${productName} ${variantPart}`.trim();



                    tr.innerHTML = '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' + (

                            count++) + '</td>' +

                        '<td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">' +

                        '<span data-name-display="true">' + fullName + '</span>' +

                        '<input type="hidden" name="items[' + count + '][name]" value="' + fullName +

                        '">' +

                        '<input type="hidden" name="items[' + count + '][variation_id]" value="' +

                        variant

                        .id + '">' +

                        '<input type="hidden" name="items[' + count + '][variation_value_id]" value="' +

                        val

                        .id + '">' +

                        '</td>' +

                        '<td class="px-6 py-4">' +

                        '<input type="text" name="items[' + count +

                        '][reference_number]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" placeholder="Reference Number">' +

                        '</td>' +

                        '<td class="px-6 py-4">' +

                        '<select name="items[' + count +

                        '][product_types][]" multiple class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 select2-enable">' +

                        productTypesHtml +

                        '</select>' +

                        '</td>' +

                        '<td class="px-6 py-4">' +

                        '<input type="number" step="0.01" name="items[' + count +

                        '][selling_price]" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" placeholder="0.00">' +

                        '</td>' +

                        '<td class="px-6 py-4 text-right whitespace-nowrap">' +

                        '<button type="button" onclick="this.closest(\'tr\').remove(); checkCreateButton();" class="group inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-medium text-red-600 transition-all hover:bg-red-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">' +

                        'Remove' +

                        '</button>' +

                        '</td>';

                    tbody.appendChild(tr);



                    // Initialize Select2 for the newly added product types select

                    const newSelect = tr.querySelector('select[name^="items"][name$="[product_types][]"]');

                    if (newSelect) {

                        $(newSelect).select2();

                    }

                });

            });

            checkCreateButton();
        }

        function checkCreateButton() {
            const tbody = document.getElementById('productItemsBody');
            const btn = document.getElementById('createProductBtn');
            // Enable create button if there is at least one row
            btn.disabled = tbody.children.length === 0;
        }



        // --- Product Search & Details Logic ---
        const nameInput = document.getElementById('productNameInput');
        const suggestionsBox = document.getElementById('productSuggestions');
        const suggestionList = document.getElementById('suggestionList');

        let debounceTimer;

        nameInput.addEventListener('input', function() {
            const existingProductId = document.getElementById('existingProductId').value;
            const newProductName = this.value;
            const mode = document.querySelector('input[name="product_mode"]:checked').value;

            if (existingProductId !== '') {
                // This is an existing product. Typing a new name means we want to create a new product,
                // so we reset everything to a clean slate.
                document.getElementById('existingProductId').value = '';
                document.getElementById('productDescription').value = '';
                document.getElementById('productItemsBody').innerHTML = '';
                addedVariants = [];
                existingProductVariations = []; // Clear existing variations
                renderVariantTable();
                checkCreateButton();
                checkGenerateButton();
            } else {
                // This is a new product definition. Live-rename the items already in the table.
                const itemsBody = document.getElementById('productItemsBody');
                itemsBody.querySelectorAll('tr:not(.existing-item-row)').forEach(row => {
                    const nameDisplay = row.querySelector('[data-name-display="true"]');
                    const hiddenInput = row.querySelector('input[name$="[name]"]');
                    const brandPart = row.dataset.brandPart || '';
                    const variantPart = row.dataset.variantPart || '';

                    if (nameDisplay && hiddenInput) {
                        const newFullName = `${brandPart}${newProductName} ${variantPart}`.trim();
                        nameDisplay.textContent = newFullName;
                        hiddenInput.value = newFullName;
                    }
                });
            }

            // --- Search logic should only run in standard mode ---
            if (mode === 'standard') {
                clearTimeout(debounceTimer);
                const query = newProductName;

                if (query.length < 2) {
                    suggestionsBox.classList.add('hidden');
                    return;
                }

                debounceTimer = setTimeout(() => {
                    fetch('{{ route('product.search') }}?query=' + query)
                        .then(response => response.json())
                        .then(data => {
                            suggestionList.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(product => {
                                    const li = document.createElement('li');
                                    li.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer";
                                    li.textContent = product.product_name;
                                    li.onclick = () => selectProduct(product);
                                    suggestionList.appendChild(li);
                                });
                                suggestionsBox.classList.remove('hidden');
                            } else {
                                suggestionsBox.classList.add('hidden');
                            }
                        });
                }, 300);
            } else {
                // If in simple mode, make sure the suggestion box is hidden
                suggestionsBox.classList.add('hidden');
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!nameInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('hidden');
            }
        });

        function selectProduct(product) {
            nameInput.value = product.product_name;
            document.getElementById('productDescription').value = product.product_description || '';
            document.getElementById('existingProductId').value = product.id;

            suggestionsBox.classList.add('hidden');

            // Always trigger item check after selecting a product. Filtering will be handled in checkExistingItems.
            checkExistingItems();
        }

        function openDetailsModal() {
            const productId = document.getElementById('existingProductId').value;
            if (!productId) return;

            fetch('{{ route('product.items.fetch') }}?product_id=' + productId) // Fetch all items for product
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('detailsModalBody');
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        tbody.innerHTML =
                            '<tr><td colspan="4" class="px-6 py-4 text-center">No existing items found.</td></tr>';
                    } else {
                        data.forEach(item => {
                            tbody.innerHTML += '<tr>' +
                                '<td class="px-6 py-4">' + item.product_name + '</td>' +
                                '<td class="px-6 py-4">' + (item.variation_value ? item.variation_value
                                    .variation_value : '-') + '</td>' +
                                '<td class="px-6 py-4 text-right">' + (item.ref_number_auto || '-') + '</td>' +
                                '<td class="px-6 py-4 text-right">' + (item.reference_number || '-') + '</td>' +
                                '</tr>';
                        });
                    }
                    document.getElementById('detailsModal').classList.remove('hidden');
                });
        }

        function closeDetailsModal() {
            document.getElementById('detailsModal').classList.add('hidden');
        }

        function checkExistingItems() {

            const productId = document.getElementById('existingProductId').value;

            const brandId = document.getElementById('brandSelect').value;



            if (!productId) return;



            let fetchUrl = '{{ route('product.items.fetch') }}?product_id=' + productId;

            if (brandId) {

                fetchUrl += '&brand_id=' + brandId;

            }



            fetch(fetchUrl)

                .then(res => res.json())

                .then(data => {

                    // Store existing variations to prevent duplicates

                    existingProductVariations = data.map(item => ({

                        pm_variation_id: item.pm_variation_id,

                        pm_variation_value_id: item.pm_variation_value_id

                    })).filter(v => v.pm_variation_id && v.pm_variation_value_id);



                    const tbody = document.getElementById('productItemsBody');

                    document.querySelectorAll('.existing-item-row').forEach(row => row.remove());



                    data.forEach((item, index) => {

                        const tr = document.createElement('tr');

                        tr.className = "bg-gray-50 existing-item-row";

                        tr.innerHTML =

                            '<td class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap">Existing</td>' +

                            '<td class="px-6 py-4 font-medium text-gray-500 whitespace-nowrap">' + item

                            .product_name +

                            ' <span class="ml-2 text-xs text-blue-500">(Existing)</span></td>' +

                            '<td class="px-6 py-4"><input type="text" value="' + (item.reference_number ||

                                '') +

                            '" disabled class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-xl cursor-not-allowed"></td>' +

                            '<td class="px-6 py-4"><select multiple disabled class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-xl cursor-not-allowed select2-enable"><option value="">No types</option></select></td>' +

                            '<td class="px-6 py-4"><input type="text" value="' + (item.selling_price ||
                            '0.00') +
                            '" disabled class="w-full px-4 py-2 border border-gray-200 bg-gray-100 rounded-xl cursor-not-allowed text-right"></td>' +

                            '<td class="px-6 py-4 text-right whitespace-nowrap"><!-- No actions for existing items --></td>';

                        tbody.prepend(tr);



                        const existingSelect = tr.querySelector(

                            'select[name^="items"][name$="[product_types][]"]');

                        if (existingSelect) {

                            $(existingSelect).select2({

                                disabled: true

                            });

                        }

                    });

                });

        }
        // --- Modals Logic ---
        function openAddVariantModal() {
            document.getElementById('addVariantModal').classList.remove('hidden');
        }

        function closeAddVariantModal() {
            document.getElementById('addVariantModal').classList.add('hidden');
            document.getElementById('newVariantName').value = '';
        }

        function saveNewVariant() {
            const name = document.getElementById('newVariantName').value;
            if (!name) return Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Name is required'
            });

            fetch("{{ route('variations.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        variation_name: name
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Reload variants
                        fetch("{{ route('variations.fetch') }}")
                            .then(res => res.json())
                            .then(variants => {
                                const select = document.getElementById('variantSelect');
                                // Keep selected if possible, but here we just refresh list
                                // If we want to auto-select the new one, we'd need to know its ID.
                                // For now, just refresh options.
                                const currentVal = select.value;
                                select.innerHTML = '<option value="">Select Variant Name</option>';
                                variants.forEach(v => {
                                    select.innerHTML += '<option value="' + v.id + '">' + v.variation_name +
                                        '</option>';
                                });
                                $(select).val(currentVal).trigger('change'); // Restore selection if valid or clear
                                closeAddVariantModal();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: 'Variant added successfully'
                                });
                            });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error saving variant'
                        });
                    }
                });
        }

        function openAddVariantValueModal() {
            const variantId = document.getElementById('variantSelect').value;
            if (!variantId) return Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a Variant Name first.'
            });
            document.getElementById('addVariantValueModal').classList.remove('hidden');
        }

        function closeAddVariantValueModal() {
            document.getElementById('addVariantValueModal').classList.add('hidden');
            document.getElementById('newVariantValue').value = '';
            document.getElementById('newVariantUnit').value = '';
        }

        function saveNewVariantValue() {
            const variantId = document.getElementById('variantSelect').value;
            const value = document.getElementById('newVariantValue').value;
            const unitId = document.getElementById('newVariantUnit').value;

            if (!value || !unitId) return Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'All fields are required'
            });

            fetch("{{ route('variationValues.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        variation_id: variantId,
                        variation_value: value,
                        unit_of_measurement_id: unitId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        loadVariationValues(variantId);
                        closeAddVariantValueModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Variant value added successfully'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error saving value'
                        });
                    }
                });
        }


        function submitProductForm() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to create a new product.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, create it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = $('#productForm').serialize();
                    // const csrf_token = "{{ csrf_token() }}";

                    jQuery.ajax({
                        url: "{{ route('product.store') }}",
                        type: "POST",
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    confirmButtonColor: '#3B82F6'
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('productManagement.index') }}";
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message,
                                    confirmButtonColor: '#EF4444'
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'An error occurred';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message,
                                confirmButtonColor: '#EF4444'
                            });
                        }
                    });
                }
            });
        }
    </script>

    <!-- Add Variant Modal -->
    <div id="addVariantModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/75 backdrop-blur-sm" aria-hidden="true"
                onclick="closeAddVariantModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Add New Variant</h3>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Variant Name</label>
                        <input type="text" id="newVariantName"
                            class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveNewVariant()"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                    <button type="button" onclick="closeAddVariantModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Variant Value Modal -->
    <div id="addVariantValueModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900/75 backdrop-blur-sm" aria-hidden="true"
                onclick="closeAddVariantValueModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="relative z-10 inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Add New Variant Value</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Value</label>
                            <input type="text" id="newVariantValue"
                                class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Unit of Measurement</label>
                            <select id="newVariantUnit"
                                class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white">
                                <option value="">Select Unit</option>
                                @foreach ($unitOfMeasurement as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="saveNewVariantValue()"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                    <button type="button" onclick="closeAddVariantValueModal()"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@endsection
