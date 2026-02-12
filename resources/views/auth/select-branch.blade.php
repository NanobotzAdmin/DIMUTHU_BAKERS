@extends('layouts.guest')
@section('title', 'Select Branch')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 relative overflow-hidden">
        
    <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-amber-100/50 to-transparent -z-10"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-orange-100 rounded-full blur-3xl opacity-50 -z-10"></div>

    <div class="max-w-3xl w-full bg-white rounded-3xl shadow-xl border border-stone-100 overflow-hidden">
        
        <div class="px-8 pt-10 pb-6 text-center border-b border-stone-100">
            <div class="mx-auto h-16 w-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-6 shadow-sm rotate-3 hover:rotate-0 transition-transform duration-300">
                <i class="bi bi-shop-window text-amber-600 text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-stone-900 tracking-tight">
                Welcome Back
            </h2>
            <p class="mt-2 text-stone-500 text-sm max-w-sm mx-auto">
                Please select your bakery branch to load the correct inventory and sales dashboard.
            </p>
        </div>

        <div class="p-8 bg-stone-50/50">
            
            @if (session('error'))
            <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-100 flex items-start">
                <i class="bi bi-exclamation-circle-fill text-red-500 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-semibold text-red-800">Access Denied</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <form id="select-branch-form" action="{{ route('selectBranch.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($branches as $branch)
                    <label class="relative group cursor-pointer">
                        <input type="radio" name="branch_id" value="{{ $branch->id }}" class="peer sr-only" required>
                        
                        <div class="p-5 bg-white rounded-xl border-2 border-transparent shadow-sm hover:shadow-md transition-all duration-200 
                                  peer-checked:border-amber-500 peer-checked:bg-amber-50/30 peer-checked:shadow-none
                                  group-hover:border-stone-200">
                            
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-lg bg-stone-100 flex items-center justify-center text-stone-500 group-hover:text-amber-600 group-hover:bg-amber-50 transition-colors peer-checked:bg-amber-100 peer-checked:text-amber-600">
                                        <i class="bi bi-shop"></i>
                                    </div>
                                    
                                    <div>
                                        <h3 class="font-semibold text-stone-900">{{ $branch->name }}</h3>
                                        <p class="text-xs text-stone-500 font-medium uppercase tracking-wide">{{ $branch->city ?? 'Main Hub' }}</p>
                                    </div>
                                </div>

                                <div class="h-5 w-5 rounded-full border border-gray-300 group-has-[:checked]:bg-amber-600 group-has-[:checked]:border-transparent flex items-center justify-center transition-colors">
                                <i class="bi bi-check text-white text-xs opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></i>
                            </div>
                            </div>

                            @if($branch->code)
                            <div class="mt-3 flex items-center">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-stone-100 text-stone-600 border border-stone-200">
                                    #{{ $branch->code }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>
            </form>
        </div>

        <div class="px-8 py-6 bg-white border-t border-stone-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4">
            
            <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto flex items-center justify-center px-4 py-2 text-sm font-medium text-stone-500 hover:text-stone-800 transition-colors rounded-lg hover:bg-stone-50">
                    <i class="bi bi-box-arrow-left mr-2"></i>
                    Sign out
                </button>
            </form>

            <button type="submit" form="select-branch-form" class="w-full sm:w-auto group inline-flex items-center justify-center py-2.5 px-6 border border-transparent text-sm font-medium rounded-xl text-white bg-stone-900 hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 shadow-lg hover:shadow-xl transition-all duration-200">
                Open Dashboard
                <i class="bi bi-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </button>
        </div>

    </div>
    
    <p class="mt-8 text-center text-xs text-stone-400">
        &copy; {{ date('Y') }} Bakery Mate System. All rights reserved.
    </p>

</div>
@endsection