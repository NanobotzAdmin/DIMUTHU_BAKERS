{{-- 
    resources/views/settings/branding.blade.php 
--}}

<form id="branding-settings-form" action="{{ route('adminSettings.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Logo (Dark)</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="primary_logo" id="primary_logo" accept="image/png,image/jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this, 'primary_logo_preview')">
                        <div id="primary_logo_preview_container" class="{{ ($settings->logos->primary ?? '') ? '' : 'hidden' }} mb-2">
                            <img id="primary_logo_preview" src="{{ asset($settings->logos->primary ?? '') }}" class="mx-auto h-16 w-auto object-contain">
                        </div>
                        <div id="primary_logo_placeholder" class="{{ ($settings->logos->primary ?? '') ? 'hidden' : '' }} space-y-1">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-gray-500">Upload primary logo</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 400x100px, PNG (For light backgrounds)</p>
                </div>

                {{-- White Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">White Logo (Light)</label>
                    <div class="relative border-2 border-dashed border-slate-700 bg-slate-800 rounded-lg p-6 text-center hover:bg-slate-700 transition-colors">
                        <input type="file" name="white_logo" id="white_logo" accept="image/png,image/jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this, 'white_logo_preview')">
                        <div id="white_logo_preview_container" class="{{ ($settings->logos->white ?? '') ? '' : 'hidden' }} mb-2">
                            <img id="white_logo_preview" src="{{ asset($settings->logos->white ?? '') }}" class="mx-auto h-16 w-auto object-contain">
                        </div>
                        <div id="white_logo_placeholder" class="{{ ($settings->logos->white ?? '') ? 'hidden' : '' }} space-y-1">
                            <svg class="mx-auto h-8 w-8 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-slate-300">Upload white logo</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 400x100px, PNG (For sidebars/dark backgrounds)</p>
                </div>

                {{-- Login Logo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Login Page Logo</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="login_logo" id="login_logo" accept="image/png,image/jpeg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this, 'login_logo_preview')">
                        <div id="login_logo_preview_container" class="{{ ($settings->logos->login ?? '') ? '' : 'hidden' }} mb-2">
                            <img id="login_logo_preview" src="{{ asset($settings->logos->login ?? '') }}" class="mx-auto h-16 w-auto object-contain">
                        </div>
                        <div id="login_logo_placeholder" class="{{ ($settings->logos->login ?? '') ? 'hidden' : '' }} space-y-1">
                            <svg class="mx-auto h-8 w-8 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-gray-500">Upload login logo</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Recommended: 512x512px, PNG</p>
                </div>


                {{-- Favicon --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 transition-colors">
                        <input type="file" name="favicon" id="favicon" accept=".ico,image/png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this, 'favicon_preview')">
                        <div id="favicon_preview_container" class="{{ ($settings->logos->favicon ?? '') ? '' : 'hidden' }} mb-2">
                            <img id="favicon_preview" src="{{ asset($settings->logos->favicon ?? '') }}" class="mx-auto h-16 w-auto object-contain">
                        </div>
                        <div id="favicon_placeholder" class="{{ ($settings->logos->favicon ?? '') ? 'hidden' : '' }} space-y-1">
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

    {{-- Color Palette Redesigned --}}
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex items-center gap-2 mb-6">
                <div class="w-8 h-8 bg-[#D4A017]/10 rounded flex items-center justify-center">
                    <i class="bi bi-palette text-[#D4A017]"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Color Palette & Theme</h3>
            </div>

            @php
                $colorGroups = [
                    'System Appearance' => [
                        'icon' => 'bi-laptop',
                        'colors' => [
                            'primary' => ['label' => 'Primary Brand', 'default' => '#D4A017', 'desc' => 'Main buttons, links & accents'],
                            'secondary' => ['label' => 'Secondary', 'default' => '#1F2937', 'desc' => 'Secondary actions'],
                            'background' => ['label' => 'App Background', 'default' => '#F9FAFB', 'desc' => 'Main page background'],
                            'text' => ['label' => 'Base Text', 'default' => '#111827', 'desc' => 'Standard body text'],
                            'accent' => ['label' => 'Accent', 'default' => '#FCD34D', 'desc' => 'Highlights & badges'],
                        ]
                    ],
                    'Sidebar (Main)' => [
                        'icon' => 'bi-layout-sidebar',
                        'colors' => [
                            'sidebar_bg' => ['label' => 'Background', 'default' => '#0F172A', 'desc' => 'Sidebar main color'],
                            'sidebar_text' => ['label' => 'Text Color', 'default' => '#CBD5E1', 'desc' => 'Normal menu items'],
                            'sidebar_icon' => ['label' => 'Icon Color', 'default' => '#94A3B8', 'desc' => 'Menu icons'],
                            'sidebar_hover' => ['label' => 'Hover State', 'default' => '#1E293B', 'desc' => 'Item on hover'],
                            'sidebar_active' => ['label' => 'Active State', 'default' => '#D4A017', 'desc' => 'Current active item'],
                        ]
                    ],
                    'Sidebar (Sub-topics)' => [
                        'icon' => 'bi-list-nested',
                        'colors' => [
                            'sidebar_border' => ['label' => 'Sub-menu Border', 'default' => '#334155', 'desc' => 'Left border of sub-menus'],
                            'sidebar_sub_active_bg' => ['label' => 'Active BG', 'default' => '#1E293B', 'desc' => 'Selected sub-topic background'],
                            'sidebar_sub_active_text' => ['label' => 'Active Text', 'default' => '#FCD34D', 'desc' => 'Selected sub-topic text'],
                        ]
                    ]
                ];
            @endphp

            <div class="space-y-8">
                @foreach($colorGroups as $groupName => $group)
                    <div>
                        <div class="flex items-center gap-2 mb-4 border-b border-gray-100 pb-2">
                            <i class="bi {{ $group['icon'] }} text-gray-400"></i>
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider">{{ $groupName }}</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($group['colors'] as $key => $data)
                                <div class="flex items-start gap-4 p-3 rounded-lg border border-gray-100 hover:border-[#D4A017]/30 transition-colors">
                                    {{-- Color Swatch (Large) --}}
                                    <div class="relative flex-shrink-0">
                                        <input type="color" name="colors[{{ $key }}]" id="color_picker_{{ $key }}"
                                            value="{{ old('colors.'.$key, $settings->colors->{$key} ?? $data['default']) }}"
                                            class="w-12 h-12 rounded-lg border-2 border-white shadow-sm cursor-pointer"
                                            oninput="updateColorInput('{{ $key }}', this.value)">
                                    </div>
                                    
                                    {{-- Labels and Hex Input --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-900 truncate">{{ $data['label'] }}</span>
                                            <span class="text-[10px] text-gray-500 mb-2 leading-tight">{{ $data['desc'] }}</span>
                                            <input type="text" id="color_{{ $key }}"
                                                value="{{ old('colors.'.$key, $settings->colors->{$key} ?? $data['default']) }}"
                                                class="w-full px-2 py-1 text-xs font-mono border border-gray-200 rounded color-text-input focus:border-[#D4A017] focus:ring-1 focus:ring-[#D4A017] outline-none"
                                                oninput="updateColorPicker('{{ $key }}', this.value)">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Combined Comprehensive Preview --}}
            <div class="mt-10 border-t border-gray-100 pt-8">
                <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Real-time Visual Branding Preview</h4>
                <div id="comprehensive-preview" class="rounded-xl overflow-hidden border border-gray-200 shadow-lg flex h-[300px]">
                    {{-- Mini Sidebar --}}
                    <div id="preview-sidebar" class="w-48 flex flex-col transition-colors duration-200" style="background-color: {{ $settings->colors->sidebar_bg ?? '#0F172A' }};">
                        <div class="h-14 flex items-center px-4 border-b border-white/5">
                            <div class="w-8 h-8 rounded bg-white/20"></div>
                            <div class="ml-2 w-20 h-3 rounded bg-white/10"></div>
                        </div>
                        <div class="flex-1 p-3 space-y-2">
                            <div class="h-8 rounded flex items-center px-3" style="background-color: {{ $settings->colors->sidebar_active ?? '#D4A017' }};">
                                <div class="w-4 h-4 rounded-full bg-white/30 mr-2"></div>
                                <div class="w-20 h-2 rounded bg-white/50"></div>
                            </div>
                            <div class="h-8 rounded flex items-center px-3 hover:bg-white/5">
                                <div class="w-4 h-4 rounded-full bg-white/10 mr-2"></div>
                                <div class="w-16 h-2 rounded bg-white/10"></div>
                            </div>
                            <div class="ml-4 pl-4 border-l-2 space-y-2" style="border-color: {{ $settings->colors->sidebar_border ?? '#334155' }};">
                                <div class="h-7 rounded flex items-center px-3" style="background-color: {{ $settings->colors->sidebar_sub_active_bg ?? '#1E293B' }}; color: {{ $settings->colors->sidebar_sub_active_text ?? '#FCD34D' }};">
                                    <span class="text-[10px] font-medium font-inter">Active Sub-topic</span>
                                </div>
                                <div class="h-7 rounded flex items-center px-3 text-white/40">
                                    <span class="text-[10px] font-inter">Sub-topic link</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Mini Content --}}
                    <div id="preview-main" class="flex-1 p-6 transition-colors duration-200" style="background-color: {{ $settings->colors->background ?? '#F9FAFB' }};">
                        <div class="flex justify-between mb-6">
                            <div class="w-32 h-6 rounded bg-gray-200"></div>
                            <div class="w-24 h-8 rounded" style="background-color: {{ $settings->colors->primary ?? '#D4A017' }};"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="h-20 bg-white rounded-lg shadow-sm border border-gray-100 p-3">
                                <div class="w-8 h-2 rounded bg-gray-100 mb-2"></div>
                                <div class="w-12 h-4 rounded" style="background-color: {{ $settings->colors->accent ?? '#FCD34D' }};"></div>
                            </div>
                            <div class="h-20 bg-white rounded-lg shadow-sm border border-gray-100 p-3"></div>
                            <div class="h-20 bg-white rounded-lg shadow-sm border border-gray-100 p-3"></div>
                        </div>
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-100">
                            <div class="w-full h-2 rounded bg-gray-100 mb-2"></div>
                            <div class="w-3/4 h-2 rounded bg-gray-100"></div>
                        </div>
                    </div>
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
                        @php $style = $settings->receipt_template->style ?? 'standard'; @endphp
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
                        {{ old('receipt_template.show_logo', $settings->receipt_template->show_logo ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="show_logo" class="text-sm text-gray-700 select-none">Show logo on receipt</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="receipt_template[show_qr]" id="show_qr" value="1"
                        {{ old('receipt_template.show_qr', $settings->receipt_template->show_qr ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-[#D4A017] border-gray-300 rounded focus:ring-[#D4A017]">
                    <label for="show_qr" class="text-sm text-gray-700 select-none">Show QR code</label>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="receipt_template[show_social]" id="show_social" value="1"
                        {{ old('receipt_template.show_social', $settings->receipt_template->show_social ?? false) ? 'checked' : '' }}
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
                        value="{{ old('social_media.facebook', $settings->social_media->facebook ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="facebook.com/yourbakery">
                </div>

                <div>
                    <label for="social_instagram" class="block text-sm font-medium text-gray-700 mb-1">Instagram</label>
                    <input type="text" name="social_media[instagram]" id="social_instagram" 
                        value="{{ old('social_media.instagram', $settings->social_media->instagram ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="@yourbakery">
                </div>

                <div>
                    <label for="social_twitter" class="block text-sm font-medium text-gray-700 mb-1">Twitter / X</label>
                    <input type="text" name="social_media[twitter]" id="social_twitter" 
                        value="{{ old('social_media.twitter', $settings->social_media->twitter ?? '') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#D4A017] focus:ring-[#D4A017] sm:text-sm p-2"
                        placeholder="@yourbakery">
                </div>

                <div>
                    <label for="social_website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="social_media[website]" id="social_website" 
                        value="{{ old('social_media.website', $settings->social_media->website ?? '') }}"
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

        // 2. Comprehensive Live Preview Logic
        function updateLivePreview() {
            const colors = {};
            // Gather all current values
            inputs.forEach(input => {
                if(input.name.startsWith('colors[')) {
                    const key = input.name.match(/\[(.*?)\]/)[1];
                    colors[key] = input.type === 'color' ? input.value : document.getElementById('color_picker_' + key).value;
                }
            });

            // Update Sidebar Preview
            const sidebar = document.getElementById('preview-sidebar');
            const activeItem = sidebar?.querySelector('[style*="background-color"]');
            const subActiveItem = sidebar?.querySelector('.ml-4 .rounded');
            const subMenuBorder = sidebar?.querySelector('.ml-4');
            
            if(sidebar) sidebar.style.backgroundColor = colors.sidebar_bg;
            if(activeItem) activeItem.style.backgroundColor = colors.sidebar_active;
            if(subMenuBorder) subMenuBorder.style.borderColor = colors.sidebar_border;
            if(subActiveItem) {
                subActiveItem.style.backgroundColor = colors.sidebar_sub_active_bg;
                subActiveItem.style.color = colors.sidebar_sub_active_text;
            }

            // Update Main Content Preview
            const main = document.getElementById('preview-main');
            const primaryBtn = main?.querySelector('[style*="background-color"]:not(.h-20)');
            const accentCard = main?.querySelector('.h-20 [style*="background-color"]');
            
            if(main) main.style.backgroundColor = colors.background;
            if(primaryBtn) primaryBtn.style.backgroundColor = colors.primary;
            if(accentCard) accentCard.style.backgroundColor = colors.accent;
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

        // Image Preview Logic
        window.previewImage = function(input, previewId) {
            const preview = document.getElementById(previewId);
            const container = document.getElementById(previewId + '_container');
            const placeholder = document.getElementById(previewId.replace('_preview', '') + '_placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                    checkBrandingChanges();
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

    })();
</script>