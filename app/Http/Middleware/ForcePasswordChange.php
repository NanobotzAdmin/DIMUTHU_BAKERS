<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user needs to change password
            if ($user->is_password_change == 0) {
                // Allow access to the password change route and logout route
                if (!$request->is('force-password-change*') && !$request->is('logout')) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Password change required.',
                            'redirect' => route('password.force_change')
                        ], 403);
                    }
                    return redirect()->route('password.force_change');
                }
            }
        }

        return $next($request);
    }
}
