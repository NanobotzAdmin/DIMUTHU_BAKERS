<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBranchSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!session('user_id')) { // Using session based on AuthController logic
            return redirect()->route('login');
        }

        // Exclude select-branch and logout routes to prevent loops
        if ($request->routeIs('selectBranch.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        // Check if branch is selected
        // Check if branch is selected
        $userId = session('user_id');
        $user = \App\Models\UmUser::find($userId);

        if (!$user || !$user->current_branch_id) {
            return redirect()->route('selectBranch.index')->with('error', 'Please select a branch to continue.');
        }

        return $next($request);
    }
}
