@extends('layouts.app')
@section('title', 'Admin Settings')

@section('content')
<div class="flex h-[calc(100vh-64px)] bg-gray-50"> 
    {{-- Note: Adjust h-[calc(100vh-64px)] based on your actual navbar height --}}

    <div class="w-64 bg-white border-r border-gray-200 flex-shrink-0 flex flex-col">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-sm text-gray-600 mt-1">System configuration</p>
        </div>

        <nav class="p-4 space-y-1 overflow-y-auto flex-1 custom-scrollbar">
            
            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2">
                Core
            </div>
            
            @if(Auth::user()->hasPermission('company_settings'))
            <button onclick="switchTab('company', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors bg-[#D4A017]/10 text-[#D4A017] font-medium" data-target="company">
                <i class="bi bi-building w-5 h-5"></i>
                <span>Company</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('finance_settings'))
            <button onclick="switchTab('finance', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="finance">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Finance</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('pos_settings'))
            <button onclick="switchTab('pos', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="pos">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>POS</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('users_settings'))
            <button onclick="switchTab('users', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="users">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span>Users</span>
            </button>
            @endif

            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2 mt-4">
               Operational
            </div>

            @if(Auth::user()->hasPermission('branch_settings'))
            <button onclick="switchTab('locations', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="locations">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span>Locations</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('production_settings'))
            <button onclick="switchTab('production', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="production">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                <span>Production</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('inventory_settings'))
            <button onclick="switchTab('inventory', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="inventory">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                <span>Inventory</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('dayend_settings'))
            <button onclick="switchTab('dayend', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="dayend">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <span>Day-End</span>
            </button>
            @endif

            <div class="text-xs font-medium text-gray-500 uppercase tracking-wider px-4 py-2 mt-4">
                Advanced ⭐
            </div>

            @if(Auth::user()->hasPermission('ai_settings'))
            <button onclick="switchTab('ai', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="ai">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span>AI Assistant</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('notifications_settings'))
            <button onclick="switchTab('notifications', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="notifications">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                <span>Notifications</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('integrations_settings'))
            <button onclick="switchTab('integrations', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="integrations">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                <span>Integrations</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('branding_settings'))
            <button onclick="switchTab('branding', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="branding">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                <span>Branding</span>
            </button>
            @endif

            @if(Auth::user()->hasPermission('system_settings'))
            <button onclick="switchTab('system', this)" class="tab-btn w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-colors text-gray-700 hover:bg-gray-100" data-target="system">
                <i class="bi bi-gear w-5 h-5"></i>
                <span>System</span>
            </button>
            @endif
        </nav>
    </div>

    <div class="flex-1 overflow-y-auto bg-gray-50">
        <div class="max-w-6xl mx-auto p-8">
            
            {{-- 
                Recommendation: Create separate blade files for each setting component 
                in resources/views/settings/ folder for better organization.
            --}}

            <div id="company" class="tab-content block">
                @includeIf('adminSettings.components.company')
            </div>

            <div id="finance" class="tab-content hidden">
                @includeIf('adminSettings.components.finance')
            </div>

            <div id="pos" class="tab-content hidden">
                @includeIf('adminSettings.components.pos')
            </div>

            <div id="users" class="tab-content hidden">
                @includeIf('adminSettings.components.users')
            </div>

            <div id="locations" class="tab-content hidden">
                @includeIf('adminSettings.components.locations')
            </div>

            <div id="production" class="tab-content hidden">
                @includeIf('adminSettings.components.production')
            </div>

            <div id="inventory" class="tab-content hidden">
                @includeIf('adminSettings.components.inventory')
            </div>

            <div id="dayend" class="tab-content hidden">
                @includeIf('adminSettings.components.dayend')
            </div>

            <div id="ai" class="tab-content hidden">
                @includeIf('adminSettings.components.ai')
            </div>

            <div id="notifications" class="tab-content hidden">
                @includeIf('adminSettings.components.notifications')
            </div>

            <div id="integrations" class="tab-content hidden">
                @includeIf('adminSettings.components.integrations')
            </div>

            <div id="branding" class="tab-content hidden">
                @includeIf('adminSettings.components.branding')
            </div>

             <div id="system" class="tab-content hidden">
                @includeIf('adminSettings.components.system')
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabId, buttonElement) {
        // 1. Hide all tab contents
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('block');
        });

        // 2. Show the selected tab content
        const activeContent = document.getElementById(tabId);
        if (activeContent) {
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
        }

        // 3. Reset styling for all buttons
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            // Remove active classes
            btn.classList.remove('bg-[#D4A017]/10', 'text-[#D4A017]', 'font-medium');
            // Add inactive classes
            btn.classList.add('text-gray-700', 'hover:bg-gray-100');
        });

        // 4. Apply active styling to the clicked button
        buttonElement.classList.remove('text-gray-700', 'hover:bg-gray-100');
        buttonElement.classList.add('bg-[#D4A017]/10', 'text-[#D4A017]', 'font-medium');
    }
</script>

@endsection