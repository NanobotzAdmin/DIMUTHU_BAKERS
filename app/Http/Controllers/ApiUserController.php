<?php

namespace App\Http\Controllers;

use App\Models\AdAgent;
use App\Models\UmUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


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
        // Fetch Agent Data
        $agent = AdAgent::where('user_id', $user->id)->first();
        // Attach agent to user object or keep it separate in data
        // Option 1: Attach to user (if you want it nested)
        $user->agent = $agent;
        return response()->json([
            'status' => true,
            'message' => 'Login Successful',
            'data' => [
                'user' => $user, // User now includes agent data
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
}
