<div id="quality-check-modal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 hidden">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModals()"></div>

    {{-- Modal Content --}}
    <div
        class="bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto z-[60] relative shadow-2xl flex flex-col">

        {{-- Header --}}
        <div class="p-6 border-b border-gray-100 flex-shrink-0">
            <div class="flex items-center gap-3">
                <i class="bi bi-check-circle text-3xl text-green-600"></i>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Complete Batch - Quality Check</h2>
                    <p class="text-base text-gray-500" id="qc-header-sub">Record the final output and quality inspection
                    </p>
                </div>
            </div>
            <button onclick="closeModals()"
                class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        {{-- Scrollable Body --}}
        <div class="p-6 space-y-8 overflow-y-auto flex-1">

            {{-- Batch Summary --}}
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                <div class="flex items-center gap-4">
                    <span class="text-5xl" id="qc-recipe-icon">🎂</span>
                    <div>
                        <h3 class="text-xl text-gray-900 font-bold mb-1" id="qc-recipe-name">Chocolate Cake</h3>
                        <p class="text-gray-600 flex items-center gap-2">
                            <span class="bg-white px-2 py-1 rounded border border-gray-200 text-xs font-mono"
                                id="qc-batch-id">BATCH-001</span>
                            <span>•</span>
                            <span>Target: <span class="font-bold text-gray-900" id="qc-target-qty">10</span> <span
                                    id="qc-target-unit">units</span></span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actual Output --}}
            <div>
                <label class="block text-lg font-bold text-gray-800 mb-3">
                    Actual Output Produced <span class="text-red-500">*</span>
                </label>

                <div class="flex items-center gap-3 mb-3">
                    <input type="number" id="qc-actual-output" placeholder="Enter quantity"
                        class="flex-1 h-16 px-6 text-3xl font-bold rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none transition-all placeholder:text-gray-300">
                    <span
                        class="text-2xl font-medium text-gray-500 bg-gray-50 h-16 px-5 rounded-xl border-2 border-gray-100 flex items-center justify-center min-w-[80px]"
                        id="qc-output-unit">
                        units
                    </span>
                </div>

                {{-- Quick Presets --}}
                <div class="flex gap-2 justify-start overflow-x-auto pb-2">
                    <button onclick="setActualFromTarget(0)"
                        class="px-4 py-2 bg-green-50 hover:bg-green-100 text-green-700 border border-green-200 rounded-lg text-sm font-bold transition-all flex items-center gap-1 whitespace-nowrap">
                        <i class="bi bi-check2-all"></i> All (<span id="qc-preset-all">10</span>)
                    </button>
                    <button onclick="setActualFromTarget(1)"
                        class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-lg text-sm font-medium transition-all whitespace-nowrap"
                        id="qc-btn-minus-1">
                        9 (-1)
                    </button>
                    <button onclick="setActualFromTarget(2)"
                        class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 rounded-lg text-sm font-medium transition-all whitespace-nowrap"
                        id="qc-btn-minus-2">
                        8 (-2)
                    </button>
                </div>
            </div>

            {{-- Waste Section (Conditional) --}}
            <div id="qc-waste-section" class="hidden">
                <div
                    class="bg-orange-50 border-2 border-orange-200 rounded-xl p-5 animate-in fade-in slide-in-from-top-4 duration-300">
                    <h4 class="text-lg font-bold text-orange-900 mb-4 flex items-center gap-2">
                        <i class="bi bi-trash text-xl"></i>
                        <span>Waste Detected: <span id="qc-waste-amount">0</span> <span
                                id="qc-waste-unit">units</span></span>
                    </h4>

                    <label class="block text-sm font-bold text-gray-700 mb-3">
                        Reason for Waste <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2" id="qc-waste-reasons-grid">
                        {{-- Reasons injected by JS or static list --}}
                        @foreach(['Quality Issue', 'Burnt', 'Undercooked', 'Dropped', 'Contaminated', 'Other'] as $reason)
                            <button onclick="selectWasteReason(this, '{{ $reason }}')"
                                class="waste-reason-btn p-3 rounded-xl border-2 text-sm font-medium transition-all bg-white border-gray-200 text-gray-600 hover:bg-gray-50">
                                {{ $reason }}
                            </button>
                        @endforeach
                    </div>
                    <input type="hidden" id="qc-waste-reason-val">
                </div>
            </div>

            {{-- Byproduct Recovery (Conditional for Recipes with Byproducts) --}}
            <div id="qc-byproduct-section" class="hidden">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4 justify-between">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-recycle text-green-600 text-xl"></i>
                            <h4 class="text-lg font-bold text-green-900">Waste Recovery & Byproducts</h4>
                        </div>
                        <span
                            class="bg-green-600 text-white text-xs px-2 py-1 rounded-full items-center gap-1 hidden sm:flex">
                            <i class="bi bi-leaf"></i> Sustainability
                        </span>
                    </div>

                    <div class="space-y-3" id="qc-byproduct-list">
                        {{-- Byproduct items injected here --}}
                    </div>

                    <div class="mt-4 bg-white/60 border border-green-200 rounded-lg p-3 flex items-start gap-3">
                        <i class="bi bi-info-circle-fill text-green-600 mt-0.5"></i>
                        <p class="text-xs text-green-800 leading-relaxed">
                            <span class="font-bold">Variance Guide:</span>
                            <span class="text-orange-700 font-bold">Orange (+)</span> = More waste/byproduct than
                            expected.
                            <span class="text-green-700 font-bold">Green (-)</span> = Less waste/byproduct
                            (Efficiency!).
                        </p>
                    </div>
                </div>
            </div>

            {{-- Quality Notes --}}
            <div>
                <label class="block text-lg font-bold text-gray-800 mb-2">Quality Notes <span
                        class="text-gray-400 font-normal text-sm ml-1">(Optional)</span></label>
                <textarea id="qc-notes"
                    placeholder="Add observations about texture, taste, visual appeal, or any issues..."
                    class="w-full h-32 px-4 py-3 text-base rounded-xl border-2 border-gray-200 focus:border-green-500 focus:ring-4 focus:ring-green-100 outline-none resize-none transition-all placeholder:text-gray-400"></textarea>
            </div>

            {{-- Photos --}}
            <div>
                <label class="block text-lg font-bold text-gray-800 mb-2">Quality Photos <span
                        class="text-gray-400 font-normal text-sm ml-1">(Optional)</span></label>
                <button
                    class="w-full h-24 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-400 hover:bg-blue-50/50 transition-all flex flex-col items-center justify-center gap-2 text-gray-500 hover:text-blue-600 group">
                    <i class="bi bi-camera text-2xl group-hover:scale-110 transition-transform"></i>
                    <span class="font-medium">Tap to capture or upload photos</span>
                </button>
            </div>

        </div>

        {{-- Footer Actions --}}
        <div class="p-6 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl flex-shrink-0">
            <div class="flex gap-4">
                <button onclick="closeModals()"
                    class="flex-1 h-14 bg-white border-2 border-gray-200 hover:bg-gray-50 hover:border-gray-300 text-gray-700 rounded-xl font-bold text-lg transition-all">
                    Cancel
                </button>
                <button onclick="handleSubmitCompletion()" id="qc-submit-btn" disabled
                    class="flex-1 h-14 bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl font-bold text-lg shadow-lg shadow-green-200 hover:shadow-green-300 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    Complete Batch
                </button>
            </div>
        </div>

    </div>
</div>