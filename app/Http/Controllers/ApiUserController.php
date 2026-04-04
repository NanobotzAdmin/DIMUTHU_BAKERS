<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\UmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ApiUserController extends Controller
{
    public function login(Request $request)
    {
        Log::info($request);
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
            $user = UmUser::where('user_name', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }
            if (!Hash::check($request->password, $user->user_password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.'
                ], 401);
            }
            $token = $user->createToken('auth_token')->plainTextToken;

            $profile = null;
            // Supervisor: 2, Driver: 3 (Assumed IDs)
            if ($user->user_role_id == 10) {
                $profile = DB::table('sm_superviser')->where('user_id', $user->id)->first();
            } elseif ($user->user_role_id == 9) {
                $profile = DB::table('dm_driver')->where('user_id', $user->id)->first();
            } elseif ($user->user_role_id == 8) {
                $profile = DB::table('ad_agent')->where('user_id', $user->id)->first();
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
}
