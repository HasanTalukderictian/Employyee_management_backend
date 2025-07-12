<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    //

     public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'first_name'      => 'required|string|max:100',
            'last_name'       => 'required|string|max:100',
            'email'           => 'required|email|max:150|unique:employee,email',
            'phone'           => 'required|string|max:20|unique:employee,phone',
            'address'         => 'required|string',
            'date_of_birth'   => 'required|date',
            'gender'          => 'required|in:Male,Female,Other',
            'department_id'   => 'required|exists:department,id',
            'designation_id' => 'required|exists:desgination,id',
            'hire_date'       => 'required|date',
            'salary'          => 'required|numeric',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status'          => 'required|in:Active,Inactive',
        ]);

        // Handle profile picture upload if exists
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('uploads/profile_pictures'), $imageName);
            $validatedData['profile_picture'] = 'uploads/profile_pictures/' . $imageName;
        }

        // Create employee record
        $employee = Employee::create($validatedData);

        // Return response
        return response()->json([
            'message' => 'Employee created successfully',
            'employee' => $employee
        ], 201);
    }
}
