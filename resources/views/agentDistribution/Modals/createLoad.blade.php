<div id="create-load-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300" id="create-load-backdrop"></div>

    {{-- Slide-in Panel --}}
    <div id="create-load-panel" class="absolute top-0 right-0 h-full w-full max-w-2xl bg-white shadow-2xl overflow-hidden flex flex-col transform transition-transform duration-300 translate-x-full">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white p-6 flex-shrink-0">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Create Daily Load</h2>
                <button onclick="closeCreateLoadModal()" class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            {{-- Stepper --}}
            <div class="flex items-center justify-between px-2">
                <div class="flex flex-col items-center relative z-10" id="step-indicator-1">
                    <div class="step-circle w-10 h-10 rounded-full flex items-center justify-center bg-white text-amber-600 font-bold border-2 border-transparent transition-all">1</div>
                    <span class="text-xs font-medium text-white/90 mt-2">Details</span>
                </div>
                <div class="flex-1 h-1 bg-white/30 mx-2 rounded-full relative">
                    <div id="step-connector-1" class="absolute left-0 top-0 h-full bg-white rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="flex flex-col items-center relative z-10" id="step-indicator-2">
                    <div class="step-circle w-10 h-10 rounded-full flex items-center justify-center bg-amber-700 text-white/60 border-2 border-amber-400/50 transition-all">2</div>
                    <span class="text-xs font-medium text-white/60 mt-2">Products</span>
                </div>
                <div class="flex-1 h-1 bg-white/30 mx-2 rounded-full relative">
                    <div id="step-connector-2" class="absolute left-0 top-0 h-full bg-white rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
                <div class="flex flex-col items-center relative z-10" id="step-indicator-3">
                    <div class="step-circle w-10 h-10 rounded-full flex items-center justify-center bg-amber-700 text-white/60 border-2 border-amber-400/50 transition-all">3</div>
                    <span class="text-xs font-medium text-white/60 mt-2">Review</span>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6" id="wizard-content">
            
            <!-- Step 1: Basic Info -->
            <div id="step-content-1" class="wizard-step">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Route & Agent Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Select Agent</label>
                        <select id="load-agent" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-amber-500">
                            <option value="">Choose Agent...</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->agent_code }} - {{ $agent->agent_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Select Route</label>
                        <select id="load-route" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-amber-500">
                            <option value="">Choose Route...</option>
                            @foreach($routes as $route)
                                <option value="{{ $route->id }}">{{ $route->route_code }} - {{ $route->route_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Load Date</label>
                        <input type="date" id="load-date" value="{{ date('Y-m-d') }}" class="w-full h-12 px-4 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-amber-500">
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2 font-medium">Notes</label>
                        <textarea id="load-notes" rows="3" class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-amber-500 resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Step 2: Products -->
            <div id="step-content-2" class="wizard-step hidden">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Products</h3>

                {{-- Cart Summary --}}
                <div id="load-cart-summary" class="hidden bg-amber-50 rounded-xl p-4 border border-amber-200 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-amber-900 font-medium">Total Items: <span id="cart-count">0</span></span>
                        <span class="text-amber-900 font-bold text-lg">Rs. <span id="cart-total">0.00</span></span>
                    </div>
                    <div id="cart-items-preview" class="space-y-2 max-h-40 overflow-y-auto">
                        <!-- Items injected here -->
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2 font-medium">Search Product</label>
                    <select id="product-search-select" class="w-full" style="width: 100%;"></select>
                    <p class="text-sm text-gray-500 mt-1">Start typing to search products...</p>
                </div>
            </div>

            <!-- Step 3: Review -->
            <div id="step-content-3" class="wizard-step hidden">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Load</h3>
                
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200 space-y-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="block text-gray-500">Agent</span>
                            <span class="font-medium text-gray-900" id="review-agent">-</span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Route</span>
                            <span class="font-medium text-gray-900" id="review-route">-</span>
                        </div>
                        <div>
                            <span class="block text-gray-500">Date</span>
                            <span class="font-medium text-gray-900" id="review-date">-</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="font-medium text-gray-900 mb-2">Items</h4>
                        <div id="review-items-list" class="space-y-2">
                            <!-- Review list -->
                        </div>
                        <div class="border-t border-gray-200 mt-4 pt-2 flex justify-between font-bold text-gray-900">
                            <span>Total Value</span>
                            <span>Rs. <span id="review-total-value">0.00</span></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 border-t border-gray-200 p-6 flex items-center justify-between flex-shrink-0">
            <button onclick="prevStep()" id="btn-back" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed" disabled>Back</button>
            <button onclick="nextStep()" id="btn-next" class="px-6 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-medium shadow-md hover:from-amber-600 hover:to-orange-700">Next</button>
            <button onclick="submitLoad()" id="btn-submit" class="hidden px-6 py-3 rounded-xl bg-green-600 text-white font-medium shadow-md hover:bg-green-700">Create Load</button>
        </div>
    </div>
</div>

<script>
    let currentStep = 1;
    let loadCart = [];

    // Modal Control
    const modal = document.getElementById('create-load-modal');
    const backdrop = document.getElementById('create-load-backdrop');
    const panel = document.getElementById('create-load-panel');

    function openCreateLoadModal() {
        modal.classList.remove('hidden');
        void modal.offsetWidth; // Trigger reflow
        
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        
        panel.classList.remove('translate-x-full');
        panel.classList.add('translate-x-0');
    }

    function closeCreateLoadModal() {
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        
        panel.classList.remove('translate-x-0');
        panel.classList.add('translate-x-full');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeCreateLoadModal();
        }
    });

    // Initialize Select2
    function initLoadProductSearch() {
        $('#product-search-select').select2({
            placeholder: 'Search for products...',
            dropdownParent: $('#create-load-panel'), // Fix z-index issues
            ajax: {
                url: '{{ route("agentDistribution.searchProductsForLoad") }}',
                dataType: 'json',
                type: 'POST',
                delay: 250,
                data: function (params) {
                    return {
                        query: params.term,
                        _token: '{{ csrf_token() }}'
                    };
                },
                processResults: function (data) {
                    return { results: data };
                },
                cache: true
            },
            minimumInputLength: 1
        }).on('select2:select', function (e) {
            addProductToCart(e.params.data);
            $(this).val(null).trigger('change');
        });
    }

    function addProductToCart(product) {
        // Fetch price if raw doesn't exist or verify
        // The API returns price_raw
        
        let existing = loadCart.find(p => p.id == product.id);
        if(existing) {
            existing.quantity++;
            existing.total = existing.quantity * existing.price;
        } else {
            loadCart.push({
                id: product.id,
                name: product.product_name,
                price: parseFloat(product.price_raw || 0),
                quantity: 1,
                total: parseFloat(product.price_raw || 0)
            });
        }
        renderLoadCart();
    }

    function renderLoadCart() {
        const container = document.getElementById('cart-items-preview');
        container.innerHTML = '';
        let totalVal = 0;
        let totalCount = 0;

        loadCart.forEach((item, index) => {
            totalVal += item.total;
            totalCount += 1; // Item types count
            
            const div = document.createElement('div');
            div.className = 'flex items-center justify-between text-sm bg-white p-2 rounded border border-gray-100';
            div.innerHTML = `
                <div>
                    <div class="font-medium text-gray-800">${item.name}</div>
                    <div class="text-xs text-gray-500">Rs. ${item.price.toFixed(2)} x <input type="number" min="0.1" step="0.1" value="${item.quantity}" onchange="updateItemQty(${index}, this.value)" class="w-16 border rounded px-1 text-center"></div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-amber-700">Rs. ${item.total.toFixed(2)}</span>
                    <button onclick="removeItem(${index})" class="text-red-400 hover:text-red-600"><i class="bi bi-trash"></i></button>
                </div>
            `;
            container.appendChild(div);
        });

        document.getElementById('cart-count').innerText = totalCount;
        document.getElementById('cart-total').innerText = totalVal.toFixed(2);
        
        if(loadCart.length > 0) {
            document.getElementById('load-cart-summary').classList.remove('hidden');
        } else {
             document.getElementById('load-cart-summary').classList.add('hidden');
        }
    }

    function updateItemQty(index, newQty) {
        if(newQty <= 0) {
            removeItem(index);
            return;
        }
        loadCart[index].quantity = parseFloat(newQty);
        loadCart[index].total = loadCart[index].quantity * loadCart[index].price;
        renderLoadCart();
    }

    function removeItem(index) {
        loadCart.splice(index, 1);
        renderLoadCart();
    }

    function nextStep() {
        if(currentStep === 1) {
            if(!$('#load-agent').val() || !$('#load-route').val()) {
                alert('Please select agent and route');
                return;
            }
        }
        if(currentStep === 2) {
             if(loadCart.length === 0) {
                alert('Please add at least one product');
                return;
            }
            populateReview();
        }
        
        currentStep++;
        updateWizardUI();
    }

    function prevStep() {
        currentStep--;
        updateWizardUI();
    }

    function updateWizardUI() {
        // Steps
        document.querySelectorAll('.wizard-step').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-content-${currentStep}`).classList.remove('hidden');
        
        // Buttons
        document.getElementById('btn-back').disabled = (currentStep === 1);
        if(currentStep === 3) {
            document.getElementById('btn-next').classList.add('hidden');
            document.getElementById('btn-submit').classList.remove('hidden');
        } else {
            document.getElementById('btn-next').classList.remove('hidden');
            document.getElementById('btn-submit').classList.add('hidden');
        }
        
        // Indicators
        updateIndicators();
    }

    function updateIndicators() {
        for(let i=1; i<=3; i++) {
            const circle = document.querySelector(`#step-indicator-${i} .step-circle`);
            if(i === currentStep) {
                circle.classList.remove('bg-amber-700', 'text-white/60', 'bg-white', 'text-amber-600');
                circle.classList.add('bg-white', 'text-amber-600', 'border-white', 'scale-110');
            } else if(i < currentStep) {
                circle.classList.remove('bg-white', 'text-amber-600', 'border-transparent');
                circle.classList.add('bg-green-500', 'text-white', 'border-green-500');
                
                // Connector
                if(i < 3) document.getElementById(`step-connector-${i}`).style.width = '100%';
            } else {
                circle.classList.remove('bg-green-500', 'text-white', 'scale-110');
                circle.classList.add('bg-amber-700', 'text-white/60', 'border-amber-400/50');
                
                 if(i < 3) document.getElementById(`step-connector-${i}`).style.width = '0%';
            }
        }
    }

    function populateReview() {
        const agentText = $('#load-agent option:selected').text();
        const routeText = $('#load-route option:selected').text();
        const date = $('#load-date').val();
        
        $('#review-agent').text(agentText);
        $('#review-route').text(routeText);
        $('#review-date').text(date);
        
        const list = document.getElementById('review-items-list');
        list.innerHTML = '';
        let total = 0;
        
        loadCart.forEach(item => {
            total += item.total;
            list.innerHTML += `<div class="flex justify-between text-sm text-gray-600">
                <span>${item.name} x ${item.quantity}</span>
                <span>${item.total.toFixed(2)}</span>
            </div>`;
        });
        
        $('#review-total-value').text(total.toFixed(2));
    }

    function submitLoad() {
        const payload = {
            agent_id: $('#load-agent').val(),
            route_id: $('#load-route').val(),
            load_date: $('#load-date').val(),
            notes: $('#load-notes').val(),
            status: 0, // Draft
            items: loadCart,
            _token: '{{ csrf_token() }}'
        };

        $.ajax({
            url: '{{ route("agentDistribution.storeDailyLoad") }}',
            method: 'POST',
            data: payload,
            success: function(res) {
                if(res.success) {
                    alert('Load created successfully!');
                    location.reload();
                }
            },
            error: function(err) {
                alert('Error creating load: ' + (err.responseJSON?.message || 'Unknown error'));
            }
        });
    }

    // Init on load
    $(document).ready(function() {
         initLoadProductSearch();
    });
</script>