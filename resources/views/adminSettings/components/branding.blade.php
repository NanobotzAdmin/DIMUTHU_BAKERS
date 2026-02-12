{{-- 
    resources/views/settings/branding.blade.php 
--}}

<form id="branding-settings-form" action="" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- Header --}}
    <div class="flex items-center justify-between sticky top-0 bg-gray-50 z-10 py-2">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#D4A017]/10 rounded-lg flex items-center justify-center">
                {{-- Palette Icon --}}
                <svg class="w-5 h-5 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Branding & Design</h2>
                <p class="text-sm text-gray-600">Logo, colors, and receipt templates</p>
            </div>
        </div>

        {{-- Save Actions --}}
        <div id="branding-save-actions" class="hidden items-center gap-2 transition-all duration-300">
            <button type="button" onclick="resetBrandingForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                Cancel
            </button>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                Save Changes
            </button>
        </div>
    </div>

    {{-- Logo & Brand Assets --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Logo & Brand Assets</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Primary Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Logo</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="logo" id="logo" accept="image/png,image/jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-1">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-gray-500">Upload logo</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 400x100px, PNG</p>
                </div>

                {{-- Icon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="icon" id="icon" accept="image/png,image/jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-1">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-gray-500">Upload icon</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 256x256px, PNG</p>
                </div>

                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="favicon" id="favicon" accept=".ico,image/png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="space-y-1">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-gray-500">Upload favicon</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 32x32px, ICO</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Color Palette --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Color Palette</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $colors = [
                        'primary' => ['label' => 'Primary', 'default' => '#D4A017'],
                        'secondary' => ['label' => 'Secondary', 'default' => '#1F2937'],
                        'background' => ['label' => 'Background', 'default' => '#F9FAFB'],
                        'text' => ['label' => 'Text', 'default' => '#111827'],
                        'accent' => ['label' => 'Accent', 'default' => '#FCD34D'],
                    ];
                @endphp

                @foreach($colors as $key => $data)
                    <div>
                        <label for="color_{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $data['label'] }}</label>
                        <div class="flex gap-1 items-center">
                            <input type="color" id="color_picker_{{ $key }}" 
                                value="{{ old('colors.'.$key, $settings->colors[$key] ?? $data['default']) }}"
                                onchange="updateColorInput('{{ $key }}', this.value)"
                                class="w-12 h-10 rounded border border-gray-300 cursor-pointer p-1">
                            
                            <input type="text" name="colors[{{ $key }}]" id="color_{{ $key }}"
                                value="{{ old('colors.'.$key, $settings->colors[$key] ?? $data['default']) }}"
                                oninput="updateColorPicker('{{ $key }}', this.value)"
                                class="color-text-input flex-1 rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm uppercase p-2">
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Live Preview --}}
            <div id="preview-box" class="mt-6 p-6 rounded-lg transition-colors duration-200" style="background-color: {{ $settings->colors['background'] ?? '#F9FAFB' }};">
                <div id="preview-inner-box" class="p-6 rounded-lg shadow-sm transition-colors duration-200" style="background-color: {{ $settings->colors['primary'] ?? '#D4A017' }};">
                    <h4 id="preview-title" class="font-bold text-lg" style="color: {{ $settings->colors['background'] ?? '#F9FAFB' }};">
                        Preview: Your Bakery Brand
                    </h4>
                    <p id="preview-text" class="text-sm mt-1" style="color: {{ $settings->colors['background'] ?? '#F9FAFB' }};">
                        This is how your brand colors will look on the dashboard.
                    </p>
                    <button type="button" class="mt-4 px-4 py-2 rounded text-sm font-medium bg-white" style="color: {{ $settings->colors['text'] ?? '#111827' }};">
                        Sample Button
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Receipt Template --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Receipt Template</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="receipt_template" class="block text-sm font-medium text-gray-700 mb-1">Template Style</label>
                    <select name="receipt_template[style]" id="receipt_template" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2">
                        @php $style = $settings->receipt_template['style'] ?? 'standard'; @endphp
                        <option value="standard" {{ $style == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="compact" {{ $style == 'compact' ? 'selected' : '' }}>Compact</option>
                        <option value="detailed" {{ $style == 'detailed' ? 'selected' : '' }}>Detailed</option>
                        <option value="custom" {{ $style == 'custom' ? 'selected' : '' }}>Custom HTML</option>
                    </select>
                </div>
            </div>

            <div class="space-y-3 mt-4">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="receipt_template[show_logo]" id="show_logo" value="1"
                        {{ old('receipt_template.show_logo', $settings->receipt_template['show_logo'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="show_logo" class="text-sm text-gray-700 select-none">Show logo on receipt</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="receipt_template[show_qr]" id="show_qr" value="1"
                        {{ old('receipt_template.show_qr', $settings->receipt_template['show_qr'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="show_qr" class="text-sm text-gray-700 select-none">Show QR code</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="receipt_template[show_social]" id="show_social" value="1"
                        {{ old('receipt_template.show_social', $settings->receipt_template['show_social'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="show_social" class="text-sm text-gray-700 select-none">Show social media links</label>
                </div>
            </div>
        </div>
    </div>

    {{-- Social Media --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Social Media</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="social_facebook" class="block text-sm font-medium text-gray-700 mb-1">Facebook</label>
                    <input type="text" name="social_media[facebook]" id="social_facebook" 
                        value="{{ old('social_media.facebook', $settings->social_media['facebook'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="facebook.com/yourbakery">
                </div>

                <div>
                    <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <input type="text" name="social_media[instagram]" id="social_instagram" 
                        value="{{ old('social_media.instagram', $settings->social_media['instagram'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="@yourbakery">
                </div>

                <div>
                    <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter / X</label>
                    <input type="text" name="social_media[twitter]" id="social_twitter" 
                        value="{{ old('social_media.twitter', $settings->social_media['twitter'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="@yourbakery">
                </div>

                <div>
                    <label for="social_website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="social_media[website]" id="social_website" 
                        value="{{ old('social_media.website', $settings->social_media['website'] ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="www.yourbakery.com">
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Save Actions --}}
    <div id="branding-save-actions-bottom" class="hidden justify-end gap-2 pt-4 border-t border-gray-200">
        <button type="button" onclick="resetBrandingForm()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            Cancel
        </button>
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#D4A017] hover:bg-[#B8860B] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#D4A017]">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
            </svg>
            Save Changes
        </button>
    </div>

</form>

<script>
    // --- Scope Isolation ---
    (function() {
        const form = document.getElementById('branding-settings-form');
        if (!form) return;

        const saveActionsTop = document.getElementById('branding-save-actions');
        const saveActionsBottom = document.getElementById('branding-save-actions-bottom');
        const inputs = form.querySelectorAll('input, select');
        
        // Preview Elements
        const previewBox = document.getElementById('preview-box');
        const previewInner = document.getElementById('preview-inner-box');
        const previewTitle = document.getElementById('preview-title');
        const previewText = document.getElementById('preview-text');

        let initialValues = {};

        const getValue = (input) => {
            if (input.type === 'checkbox') return input.checked;
            return input.value;
        };

        // Capture initial values
        inputs.forEach(input => {
            const key = input.id || input.name;
            if(key) initialValues[key] = getValue(input);
        });

        // 1. Sync Color Picker & Input
        window.updateColorInput = function(key, value) {
            const textInput = document.getElementById('color_' + key);
            if(textInput) textInput.value = value.toUpperCase();
            updateLivePreview();
            checkBrandingChanges();
        };

        window.updateColorPicker = function(key, value) {
            const picker = document.getElementById('color_picker_' + key);
            if(picker && /^#[0-9A-F]{6}$/i.test(value)) {
                picker.value = value;
                updateLivePreview();
            }
            checkBrandingChanges();
        };

        // 2. Live Preview Logic
        function updateLivePreview() {
            const primary = document.getElementById('color_primary').value;
            const background = document.getElementById('color_background').value;
            // const text = document.getElementById('color_text').value; // Can add more elements if needed

            if(previewBox) previewBox.style.backgroundColor = background;
            if(previewInner) previewInner.style.backgroundColor = primary;
            
            // Adjust text inside primary box to contrast (using background color as a simple proxy for contrast against primary)
            if(previewTitle) previewTitle.style.color = background;
            if(previewText) previewText.style.color = background;
        }

        // 3. Change Detection
        function checkBrandingChanges() {
            let hasChanged = false;
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (key && getValue(input) !== initialValues[key]) {
                    hasChanged = true;
                }
            });

            const toggle = (el, show) => {
                if (show) {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                } else {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                }
            };

            toggle(saveActionsTop, hasChanged);
            toggle(saveActionsBottom, hasChanged);
        }

        // Listeners
        inputs.forEach(input => {
            // Color inputs handled by specific functions above, catch others here
            if(input.type !== 'color' && !input.classList.contains('color-text-input')) {
                input.addEventListener('input', checkBrandingChanges);
                input.addEventListener('change', checkBrandingChanges);
            }
        });

        // Global Reset
        window.resetBrandingForm = function() {
            inputs.forEach(input => {
                const key = input.id || input.name;
                if (!key) return;

                if (input.type === 'checkbox') {
                    input.checked = initialValues[key];
                } else {
                    input.value = initialValues[key];
                }
            });
            updateLivePreview();
            checkBrandingChanges();
        }

    })();
</script>