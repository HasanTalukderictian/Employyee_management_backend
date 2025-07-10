<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Debug: Check incoming data (optional, comment out in production)



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

    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
