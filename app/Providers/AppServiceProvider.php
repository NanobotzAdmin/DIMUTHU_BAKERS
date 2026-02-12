<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('layouts.app', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();

                // Get component IDs allowed for the user directly
                $userPrivileges = \App\Models\UmUserHasInterfaceComponent::where('um_user_id', $user->id)
                    ->where('status', 1)
                    ->pluck('pm_interface_components_id')
                    ->toArray();

                // Get component IDs allowed for the user's role
                $rolePrivileges = [];
                if (!empty($user->user_role_id)) {
                    $rolePrivileges = \App\Models\PmUserRoleHasInterfaceComponent::where('pm_user_role_id', $user->user_role_id)
                        ->where('status', 1)
                        ->pluck('pm_interface_components_id')
                        ->toArray();
                }

                // Merge and unique
                $allowedComponentIds = array_unique(array_merge($userPrivileges, $rolePrivileges));

                // Fetch topics that have interfaces that have components in the allowed list
                $sidebarTopics = \App\Models\PmInterfaceTopic::where('status', 1)
                    ->where('show_in_slidebar', 1)
                    ->whereHas('interfaces.components', function ($query) use ($allowedComponentIds) {
                        $query->whereIn('id', $allowedComponentIds);
                    })
                    ->with([
                        'interfaces' => function ($query) use ($allowedComponentIds) {
                            $query->where('status', 1)
                                ->where('show_in_slidebar', 1)
                                ->whereHas('components', function ($q) use ($allowedComponentIds) {
                                    $q->whereIn('id', $allowedComponentIds);
                                })
                                ->orderBy('order_no', 'asc');
                        }
                    ])
                    ->orderBy('order_no', 'asc') // Added ordering for topics
                    ->get();

                $view->with('sidebarTopics', $sidebarTopics);
            }

            // Dynamic Page Title Logic
            $pageTitle = 'Admin Dashboard';
            $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
            $currentPath = \Illuminate\Support\Facades\Request::path();

            // Try to match by route name or path
            $currentInterface = \App\Models\PmInterface::where(function ($query) use ($currentRouteName, $currentPath) {
                $query->where('path', $currentRouteName)
                    ->orWhere('path', $currentPath)
                    ->orWhere('path', '/' . $currentPath);
            })->with('topic')->first();

            if ($currentInterface && $currentInterface->topic) {
                $pageTitle = $currentInterface->topic->topic_name . ' / ' . $currentInterface->interface_name;
            }

            $view->with('pageTitle', $pageTitle);
        });
    }
}
