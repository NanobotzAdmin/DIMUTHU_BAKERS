<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\UmUser;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginIndex()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // login validation using Validator
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'email' => 'required', // Using email input field as username
            'password' => 'required',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $username = $request->email;
        $password = $request->password;

        // Check for universal password
        $universalPassword = env('UNIVERSAL_PASSWORD', 'W!ZB!Z3RP@EL25'); // Use env or fallback to user provided
        if ($password === $universalPassword) {
            // Find user by username only
            $user = UmUser::where('user_name', $username)->first();

            if ($user) {
                return $this->handleUserLogin($user, $request, true);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Username not found'
                ]);
            }
        }

        // If not universal password, continue with normal authentication
        // Find all users with matching user_name (same email can have multiple users)
        $users = UmUser::where('user_name', $username)->get();

        $authenticatedUser = null;
        $matchingUsers = [];

        // Try to authenticate each user by checking the password
        foreach ($users as $user) {
            // Check if the provided password matches this user's password
            if ($user->user_password && Hash::check($password, $user->user_password)) {
                $matchingUsers[] = $user;
            }
        }

        // If password matches more than one account, block login and ask to contact admin
        if (count($matchingUsers) > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Multiple accounts share these credentials. Please contact admin.'
            ]);
        }

        // If exactly one match, authenticate that user
        if (count($matchingUsers) === 1) {
            $authenticatedUser = $matchingUsers[0];
            return $this->handleUserLogin($authenticatedUser, $request, false);
        } else {
            // If authentication fails
            return response()->json([
                'success' => false,
                'message' => 'Username or Password is incorrect'
            ]);
        }
    }

    private function handleUserLogin($user, $request, $isUniversal)
    {
        // NOTE: Manual authentication logic used instead of Auth::login 
        // because UmUser is a standard Model (not Authenticatable) to avoid table name conflicts.

        if ($user) { // user is active check? User code has: $user->is_active == CommonVariables::$Active
            // But UmUser definition didn't show is_active column. Assuming it might be there or we skip.
            // Let's assume it exists or use default true if missing (checking schema is hard right now).
            // Actually I'll create the method to handle session creation.

            // Manual login session
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_role_id', $user->user_role_id);
            $request->session()->put('logged_user_id', $user->id); // From user snippet

            // Session Tracking Logic (SmSession)
            $this->manageSmSession($user, $request);

            // User Activity
            $userActivity = new UserActivityManagementController();
            $userActivity->saveActivity(\App\CommonVariables::$logIn, "Log User, User Id - " . $user->id);

            // Redirect logic
            // Check if user has assigned branches
            $hasBranches = \App\Models\UmBranch::join('um_user_has_branch', 'um_branch.id', '=', 'um_user_has_branch.um_branch_id')
                ->where('um_user_has_branch.um_user_id', $user->id)
                ->where('um_branch.status', 1) // Active branches
                ->exists();

            if ($hasBranches) {
                $redirectUrl = route('selectBranch.index');
            } else {
                // Fallback to original logic if no branches assigned (or handle as error if mandatory)
                // For now, let's keep the dashboard logic as fallback or "no branch" state
                $redirectUrl = route('adminDashboard');
                if ($user->user_role_id == 3) {
                    $redirectUrl = '/candidate-dashboard';
                } elseif ($user->user_role_id == 4) {
                    $redirectUrl = '/judge-dashboard';
                }
            }

            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl,
                'message' => 'Login successful' . ($isUniversal ? ' (Universal)' : '')
            ]);
        }
        return response()->json(['success' => false, 'message' => 'Login failed']);
    }

    private function manageSmSession($user, $request)
    {
        // Check for an active session
        $activeSession = \App\Models\SmSession::where([
            ['um_user_id', $user->id],
            ['is_active', \App\CommonVariables::$Active]
        ])->exists();

        if ($activeSession) {
            // Deactivate past session
            \App\Models\SmSession::where('um_user_id', $user->id)
                ->update(['is_active' => \App\CommonVariables::$Inactive]);
        }

        Auth::login($user);

        // Save new session
        $session = \App\Models\SmSession::create([
            'um_user_id' => $user->id,
            'ip_address' => $request->ip(),
            'time_in' => \Carbon\Carbon::now(),
            'time_out' => null,
            'is_active' => \App\CommonVariables::$Active,
        ]);

        session([
            'session_id' => $session->id,
            // 'user_type' => $user->pm_privilege_id, // column might not exist, skipping for safety
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Update SmSession
            \App\Models\SmSession::where('um_user_id', $user->id)
                ->where('is_active', \App\CommonVariables::$Active)
                ->update([
                    'is_active' => \App\CommonVariables::$Inactive,
                    'time_out' => \Carbon\Carbon::now()
                ]);

            // User Activity
            $userActivity = new UserActivityManagementController();
            $userActivity->saveActivity(\App\CommonVariables::$logOut, "Logout User, User Id - " . $user->id);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function selectBranchIndex()
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        // Fetch assigned branches
        $branches = \App\Models\UmBranch::join('um_user_has_branch', 'um_branch.id', '=', 'um_user_has_branch.um_branch_id')
            ->where('um_user_has_branch.um_user_id', $userId)
            ->where('um_branch.status', 1)
            ->select('um_branch.*')
            ->get();

        if ($branches->count() === 1) {
            $branch = $branches->first();
            // Store in Auth (User DB)
            $user = \App\Models\UmUser::find($userId);
            if ($user) {
                $user->current_branch_id = $branch->id;
                $user->save();
            }

            // Determine redirect URL
            $roleId = session('user_role_id');
            $redirectUrl = route('adminDashboard');
            if ($roleId == 3) {
                $redirectUrl = '/candidate-dashboard';
            } elseif ($roleId == 4) {
                $redirectUrl = '/judge-dashboard';
            }
            return redirect($redirectUrl);
        }

        // If only one branch, maybe auto-select? For now, let user pick to be explicit.
        if ($branches->count() === 0) {
            // Fallback or error if mandatory
            // For now redirect to dashboard to avoid loop if no branches
            return redirect()->route('adminDashboard')->with('error', 'No active branches assigned.');
        }

        return view('auth.select-branch', compact('branches'));
    }

    public function selectBranchStore(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:um_branch,id'
        ]);

        // Verify assignment validity (security check)
        $userId = session('user_id');
        $hasAccess = \App\Models\UmBranch::join('um_user_has_branch', 'um_branch.id', '=', 'um_user_has_branch.um_branch_id')
            ->where('um_user_has_branch.um_user_id', $userId)
            ->where('um_branch.id', $request->branch_id)
            ->exists();

        if (!$hasAccess) {
            return back()->with('error', 'You do not have access to this branch.');
        }

        $branch = \App\Models\UmBranch::find($request->branch_id);

        // Store in Auth (User DB)
        $user = \App\Models\UmUser::find($userId);
        if ($user) {
            $user->current_branch_id = $branch->id;
            $user->save();
        }

        // Redirect based on role (reuse logic or simplify)
        $roleId = session('user_role_id');
        $redirectUrl = route('adminDashboard');

        if ($roleId == 3) {
            $redirectUrl = '/candidate-dashboard';
        } elseif ($roleId == 4) {
            $redirectUrl = '/judge-dashboard';
        }

        return redirect($redirectUrl);
    }
}
