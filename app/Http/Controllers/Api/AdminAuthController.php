<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function login(Request $request)
{
    // Validate request data
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt login using admin guard
    if (Auth::guard('admin')->attempt($credentials)) {
        $admin = Auth::guard('admin')->user();

        // Create token
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Admin login successful',
            'admin' => $admin,
            'token' => $token
        ]);
    }

    return response()->json([
        'message' => 'Invalid credentials'
    ], 401);
}

   public function logout(Request $request)
{
    $user = $request->user();

    if ($user) {
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Admin logged out successfully'
        ]);
    }

    return response()->json([
        'message' => 'No authenticated user found'
    ], 401);
}


}
