<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    //

    public function login(Request $request)
{
    $request->validate([
        'login' => 'required|string', // email or name
        'password' => 'required|string',
    ]);

    $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    $credentials = [
        $login_type => $request->login,
        'password' => $request->password
    ];

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        return response()->json([
            'message' => 'Login successful',
            'user' => $user
        ], 200);
    }

    return response()->json([
        'message' => 'Invalid credentials'
    ], 401);
}
}
