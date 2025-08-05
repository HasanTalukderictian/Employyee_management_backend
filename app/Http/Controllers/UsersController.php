<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Store (Register) a new user.
     */
    public function store(Request $request)
{
    // Validate input
    $validatedData = $request->validate([
        'employee_id' => 'required|exists:employee,id',
        'email' => 'required|email|unique:userall,email',
        'password' => 'required|string|min:6',
        'role' => 'required|string|in:Admin,Normal User', // Adjust roles as needed
    ]);

    // Create user with hashed password and role
    $user = UsersModel::create([
        'employee_id' => $validatedData['employee_id'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
        'role' => $validatedData['role'],
    ]);

    return response()->json([
        'message' => 'User created successfully',
        'data' => $user,
    ], 201);
}


    /**
     * Login user with email and password, generate token.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt login
        $user = UsersModel::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        // Generate token
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'data' => $user,
        ], 200);
    }

    /**
     * Logout user by revoking token.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
 * Display a list of users with employee details.
 */
public function view()
{
    // Eager load employee with designation and department
    $users = UsersModel::with('employee.designation', 'employee.department')->get();

    return response()->json([
        'message' => 'User list with employee, designation, and department details',
        'data' => $users
    ], 200);
}


}
