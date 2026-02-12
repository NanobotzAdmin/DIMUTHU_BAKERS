<?php

namespace App\Http\Middleware;

use Closure;
use DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\PmInterface;

class CheckInterfacePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $currentPath = $request->path();

        // Find interface by path
        // Try exact match first, then try with leading slash if not found (or vice versa depending on DB)
        $interface = PmInterface::where('path', $currentPath)
            ->orWhere('path', '/' . $currentPath)
            ->first();

        // If interface is not managed (not in DB), we allow access (or you strictly deny, but usually allow common pages)
        if (!$interface) {
            return $next($request);
        }

        $interfaceId = $interface->id;
        $userRoleId = $user->user_role_id;
        $userId = $user->id;

        // Check 1: Role permission
        $roleHasPermission = DB::table('pm_user_role_has_interface_components')
            ->join('pm_interface_components', 'pm_user_role_has_interface_components.pm_interface_components_id', '=', 'pm_interface_components.id')
            ->where('pm_user_role_has_interface_components.pm_user_role_id', $userRoleId)
            ->where('pm_interface_components.pm_interface_id', $interfaceId)
            ->where('pm_user_role_has_interface_components.status', 1)
            ->exists();

        // Check 2: User specific permission
        $userHasPermission = DB::table('um_user_has_interface_components')
            ->join('pm_interface_components', 'um_user_has_interface_components.pm_interface_components_id', '=', 'pm_interface_components.id')
            ->where('um_user_has_interface_components.um_user_id', $userId)
            ->where('pm_interface_components.pm_interface_id', $interfaceId)
            ->where('um_user_has_interface_components.status', 1)
            ->exists();

        if ($roleHasPermission || $userHasPermission) {
            return $next($request);
        }

        // If use not assign any component in one route that user dont give go to that route .show error page.
        abort(403, 'Unauthorized action.');
    }
}
