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

public function index(Request $request)
{
    $query = Employee::with(['department', 'designation']);

    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where('first_name', 'like', "%{$searchTerm}%");
    }

    $employees = $query->get();

    return response()->json([
        'message' => 'Employees retrieved successfully',
        'data' => $employees
    ], 200);
}



public function destroy($id)
{
    // Find the employee by ID
    $employee = Employee::find($id);

    // Check if employee exists
    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Optionally delete profile picture from storage
    if ($employee->profile_picture && file_exists(public_path($employee->profile_picture))) {
        unlink(public_path($employee->profile_picture));
    }

    // Delete employee record
    $employee->delete();

    // Return success response
    return response()->json([
        'message' => 'Employee deleted successfully'
    ], 200);
}


public function update(Request $request, $id)
{
    // Find the employee by ID
    $employee = Employee::find($id);

    // Check if employee exists
    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Validate incoming request data
    $validatedData = $request->validate([
        'first_name'      => 'sometimes|required|string|max:100',
        'last_name'       => 'sometimes|required|string|max:100',
        'email'           => 'sometimes|required|email|max:150|unique:employee,email,' . $employee->id,
        'phone'           => 'sometimes|required|string|max:20|unique:employee,phone,' . $employee->id,
        'address'         => 'sometimes|required|string',
        'date_of_birth'   => 'sometimes|required|date',
        'gender'          => 'sometimes|required|in:Male,Female,Other',
        'department_id'   => 'sometimes|required|exists:department,id',
        'designation_id'  => 'sometimes|required|exists:desgination,id',
        'hire_date'       => 'sometimes|required|date',
        'salary'          => 'sometimes|required|numeric',
        'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'status'          => 'sometimes|required|in:Active,Inactive',
    ]);

    // Handle profile picture upload if exists
    if ($request->hasFile('profile_picture')) {
        // Delete old picture if exists
        if ($employee->profile_picture && file_exists(public_path($employee->profile_picture))) {
            unlink(public_path($employee->profile_picture));
        }

        // Upload new picture
        $image = $request->file('profile_picture');
        $imageName = time().'_'.$image->getClientOriginalName();
        $image->move(public_path('uploads/profile_pictures'), $imageName);
        $validatedData['profile_picture'] = 'uploads/profile_pictures/' . $imageName;
    }

    // Update employee record
    $employee->update($validatedData);

    // Return success response
    return response()->json([
        'message' => 'Employee updated successfully',
        'employee' => $employee
    ], 200);
}



public function show($id)
{
    // Find the employee with department and designation relationships
    $employee = Employee::with(['department', 'designation'])->find($id);

    // Check if employee exists
    if (!$employee) {
        return response()->json([
            'message' => 'Employee not found'
        ], 404);
    }

    // Return success response
    return response()->json([
        'message' => 'Employee retrieved successfully',
        'employee' => $employee
    ], 200);
}

}
