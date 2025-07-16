<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Store a newly created department safely.
     */
    public function store(Request $request)
    {
        // Validate input strictly to avoid injection payloads
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
        ]);

        // Use Eloquent create which uses prepared statements internally
        $department = Department::create([
            'name' => htmlspecialchars($validated['name']),
        ]);

        return response()->json([
            'message' => 'Department created successfully',
            'data' => $department
        ], 201);
    }

    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = Department::all();

        return response()->json([
            'message' => 'Departments retrieved successfully',
            'data' => $departments
        ], 200);
    }

    /**
     * Delete a department by ID.
     */
    public function destroy($id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'message' => 'Department not found'
            ], 404);
        }

        $department->delete();

        return response()->json([
            'message' => 'Department deleted successfully'
        ], 200);
    }

    /**
     * Update a department by ID securely.
     */
    public function update(Request $request, $id)
    {
        $department = Department::find($id);

        if (!$department) {
            return response()->json([
                'message' => 'Department not found'
            ], 404);
        }

        // Validate input strictly
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
        ]);

        // Update using Eloquent's safe update
        $department->update([
            'name' => htmlspecialchars($validated['name']),
        ]);

        return response()->json([
            'message' => 'Department updated successfully',
            'data' => $department
        ], 200);
    }
}
