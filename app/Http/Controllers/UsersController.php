<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;

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
            'email'       => 'required|email|unique:userall,email',
            'password'    => 'required|string|min:6',
            'role'        => 'required|string|in:admin,user',
        ]);

        // Save original password for email
        $plainPassword = $validatedData['password'];

        // Create user with hashed password
        $user = UsersModel::create([
            'employee_id' => $validatedData['employee_id'],
            'email'       => $validatedData['email'],
            'password'    => Hash::make($plainPassword),
            'role'        => $validatedData['role'],
        ]);

        // Send email to user (optional failure catch)
        try {
            Mail::to($user->email)->send(new UserCreatedMail($user->email, $plainPassword));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User created, but failed to send email',
                'data'    => $user,
                'error'   => $e->getMessage(),
            ], 201);
        }

        return response()->json([
            'message' => 'User created successfully and email sent',
            'data'    => $user,
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

        // Check role (optional)
        $roleMessage = ($user->role === 'admin') ? 'Welcome Admin!' : 'Welcome User!';

        // Generate token (Sanctum)
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'role_message' => $roleMessage,
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


    /**
     * Delete a user by ID.
     */
    public function destroy($id)
    {
        $user = UsersModel::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found.',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ], 200);
    }

    public function sendmail()
    {
        Mail::raw('This is a test email.', function ($message) {
            $message->to('your-email@example.com') // Change to your test email
                ->subject('Test Email');
        });

        return response()->json(['message' => 'Test email sent!']);
    }
}
