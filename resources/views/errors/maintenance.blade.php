@extends('layouts.app')
@section('title', 'Maintenance')

@section('content')

    <div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-10 sm:px-6 lg:px-8">

        <div class="w-full max-w-lg text-center">

            <div class="mb-8 p-4">
                <img src="{{URL::asset('images/authentication/maintenance-img.png')}}"
                    class="mx-auto h-auto w-full max-w-sm object-contain" alt="Maintenance">
            </div>

            <div class="mb-8">
                <h2 class="mb-3 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Under Maintenance
                </h2>
                <p class="mb-4 text-lg text-gray-600">
                    We are currently performing scheduled maintenance. Please check back later.
                </p>

                <div class="mt-6">
                    <a href="{{route('adminDashboard')}}"
                        class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-6 py-3 text-base font-medium text-white transition-colors duration-200 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300">
                        <i class="ti ti-chevron-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection