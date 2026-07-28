<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\UmUser;
use App\Models\Notification;
use App\Models\HsGuideVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ApiUserController extends Controller
{
    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);
    }

    try {

        // Universal password
        $masterPassword = env('MASTER_PASSWORD');

        $user = UmUser::where('user_name', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // Normal password OR master password
        if (
            !Hash::check($request->password, $user->user_password) &&
            $request->password !== $masterPassword
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $profile = null;

        if ($user->user_role_id == 10) {
            $profile = DB::table('sm_superviser')
                ->where('user_id', $user->id)
                ->first();

        } elseif ($user->user_role_id == 9) {
            $profile = DB::table('dm_driver')
                ->where('user_id', $user->id)
                ->first();

        } elseif ($user->user_role_id == 8) {
            $profile = DB::table('ad_agent')
                ->where('user_id', $user->id)
                ->first();
        }

        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'data' => [
                'user' => $user,
                'profile' => $profile,
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 200);

    } catch (\Exception $e) {

        Log::error('Login error: ' . $e->getMessage());

        return response()->json([
            'status' => false,
            'message' => 'An error occurred during login'
        ], 500);
    }
}

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    public function updateFcmToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $user->fcm_token = $request->fcm_token;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'FCM Token updated successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('FCM Token update error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during token update'
            ], 500);
        }
    }

    public function forcePasswordChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $user->user_password = Hash::make($request->password);
            $user->is_password_change = 1;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password updated successfully',
                'data' => [
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Force password change error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during password update'
            ], 500);
        }
    }
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'role_specific_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();
            $user = $request->user();
            
            if ($request->has('first_name')) $user->first_name = $request->first_name;
            if ($request->has('last_name')) $user->last_name = $request->last_name;
            if ($request->has('contact_no')) $user->contact_no = $request->contact_no;
            $user->save();

            $fullName = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

            $profile = null;
            if ($user->user_role_id == 10) {
                $profileModel = DB::table('sm_superviser')->where('user_id', $user->id)->first();
                if ($profileModel) {
                    $updateData = ['superviser_name' => $fullName];
                    if ($request->has('contact_no')) $updateData['contact_number'] = $request->contact_no;
                    if ($request->has('address')) $updateData['address'] = $request->address;
                    
                    DB::table('sm_superviser')->where('user_id', $user->id)->update($updateData);
                    $profile = DB::table('sm_superviser')->where('user_id', $user->id)->first();
                }
            } elseif ($user->user_role_id == 8) {
                $profileModel = DB::table('ad_agent')->where('user_id', $user->id)->first();
                if ($profileModel) {
                    $updateData = ['agent_name' => $fullName];
                    if ($request->has('contact_no')) $updateData['phone'] = $request->contact_no;
                    if ($request->has('address')) $updateData['address'] = $request->address;
                    
                    DB::table('ad_agent')->where('user_id', $user->id)->update($updateData);
                    $profile = DB::table('ad_agent')->where('user_id', $user->id)->first();
                }
            } elseif ($user->user_role_id == 9) {
                $profileModel = DB::table('dm_driver')->where('user_id', $user->id)->first();
                if ($profileModel) {
                    $updateData = ['driver_name' => $fullName];
                    if ($request->has('contact_no')) $updateData['contact_number'] = $request->contact_no;
                    if ($request->has('address')) $updateData['address'] = $request->address;
                    
                    DB::table('dm_driver')->where('user_id', $user->id)->update($updateData);
                    $profile = DB::table('dm_driver')->where('user_id', $user->id)->first();
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully',
                'data' => [
                    'user' => $user,
                    'profile' => $profile
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Profile update error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during profile update',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get notifications for the authenticated user (paginated, latest first).
     */
    public function getNotifications(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 20);
            $user = $request->user();

            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'data' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Fetch notifications error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch notifications',
            ], 500);
        }
    }

    /**
     * Mark notifications as read.
     * Accepts: { notification_ids: [1,2,3] } or { all: true }
     */
    public function markNotificationsRead(Request $request)
    {
        try {
            $user = $request->user();

            if ($request->input('all') === true) {
                Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);
            } elseif ($request->has('notification_ids')) {
                Notification::where('user_id', $user->id)
                    ->whereIn('id', $request->notification_ids)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Notifications marked as read',
            ]);
        } catch (\Exception $e) {
            Log::error('Mark notifications read error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to mark notifications as read',
            ], 500);
        }
    }

    /**
     * Get unread notification count for badge display.
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $count = Notification::where('user_id', $request->user()->id)
                ->where('is_read', false)
                ->count();

            return response()->json([
                'status' => true,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            Log::error('Unread count error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to get unread count',
            ], 500);
        }
    }

    /**
     * Get guide videos for the authenticated user's role.
     */
    public function getGuideVideos(Request $request)
    {
        try {
            $user = $request->user();
            $userRoleId = $user->user_role_id;

            $videos = HsGuideVideo::where('status', 1)
                ->whereHas('userRoles', function ($query) use ($userRoleId) {
                    $query->where('pm_user_role.id', $userRoleId);
                })
                ->orderBy('display_order')
                ->orderBy('created_at', 'desc')
                ->get(['id', 'title', 'description', 'video_url', 'thumbnail_url', 'display_order']);

            return response()->json([
                'status' => true,
                'data' => $videos,
            ]);
        } catch (\Exception $e) {
            Log::error('Guide videos fetch error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch guide videos',
            ], 500);
        }
    }

    public function trackLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'agent_id' => 'nullable|integer',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $agentId = $request->agent_id;

            // If agent_id not provided, try to find it from the user
            if (!$agentId) {
                $agent = AdAgent::where('user_id', $user->id)->first();
                if ($agent) {
                    $agentId = $agent->id;
                }
            }

            if (!$agentId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent not found for the authenticated user.'
                ], 404);
            }

            $date = $request->date ? \Carbon\Carbon::parse($request->date) : \Carbon\Carbon::now();

            $tracking = \App\Models\AdAgentTracking::create([
                'agent_id' => $agentId,
                'lat' => $request->lat,
                'long' => $request->long,
                'date' => $date,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Location tracked successfully',
                'data' => $tracking
            ], 200);

        } catch (\Exception $e) {
            Log::error('Agent location track error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during location tracking'
            ], 500);
        }
    }

    public function trackSupervisorLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'superviser_id' => 'nullable|integer',
            'agent_id' => 'nullable|integer',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = $request->user();
            $supervisorId = $request->superviser_id;
            $agentId = $request->agent_id;

            // If superviser_id not provided, try to find it from the user (role = 10)
            if (!$supervisorId) {
                $supervisor = DB::table('sm_superviser')->where('user_id', $user->id)->first();
                if ($supervisor) {
                    $supervisorId = $supervisor->id;
                    if (!$agentId) {
                        $agentId = $supervisor->agent_id;
                    }
                }
            }

            if (!$supervisorId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supervisor profile not found for the authenticated user.'
                ], 404);
            }

            $date = $request->date ? \Carbon\Carbon::parse($request->date) : \Carbon\Carbon::now();

            $tracking = \App\Models\SmSupervisorTracking::create([
                'superviser_id' => $supervisorId,
                'agent_id' => $agentId,
                'lat' => $request->lat,
                'long' => $request->long,
                'date' => $date,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Supervisor location tracked successfully',
                'data' => $tracking
            ], 200);

        } catch (\Exception $e) {
            Log::error('Supervisor location track error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during location tracking'
            ], 500);
        }
    }
}
