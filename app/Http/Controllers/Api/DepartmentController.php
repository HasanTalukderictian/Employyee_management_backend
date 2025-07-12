<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{


   public function store(Request $request)
{

    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Create new department
    $department = Department::create([
        'name' => $validated['name'],
    ]);

    // Return success response
    return response()->json([
        'message' => 'Department created successfully',
        'data' => $department
    ], 201);
}



public function index()
{
    // Get all departments
    $departments = Department::all();

    // Return success response
    return response()->json([
        'message' => 'Departments retrieved successfully',
        'data' => $departments
    ], 200);
}


public function destroy($id)
{
    // Find the department by ID
    $department = Department::find($id);

    // Check if department exists
    if (!$department) {
        return response()->json([
            'message' => 'Department not found'
        ], 404);
    }

    // Delete the department
    $department->delete();

    // Return success response
    return response()->json([
        'message' => 'Department deleted successfully'
    ], 200);
}




public function update(Request $request, $id)
{
    // Find the department by ID
    $department = Department::find($id);

    // Check if department exists
    if (!$department) {
        return response()->json([
            'message' => 'Department not found'
        ], 404);
    }

    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
    ]);

    // Update department details
    $department->update([
        'name' => $validated['name'],
    ]);

    // Return success response
    return response()->json([
        'message' => 'Department updated successfully',
        'data' => $department
    ], 200);
}

}
