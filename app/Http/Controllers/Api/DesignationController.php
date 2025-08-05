<?php

namespace App\Http\Controllers\Api;
use App\Models\Desgination;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Desgination::query();

    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where('name', 'like', '%' . $searchTerm . '%');
    }

    $desgination = $query->get();

    return response()->json([
        'message' => 'Desgination retrieved successfully',
        'data' => $desgination
    ], 200);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Debug: Check incoming data (optional, comment out in production)



    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
    ]);

    // Create new Desgination
    $desgination = Desgination::create([
        'name' => $validated['name'],
    ]);

    // Return success response
    return response()->json([
        'message' => 'Desgination created successfully',
        'data' => $desgination
    ], 201);
}





  public function update(Request $request, $id)
{
    // Find the department by ID
    $desgination = Desgination::find($id);

    // Check if department exists
    if (!$desgination) {
        return response()->json([
            'message' => 'Desgination not found'
        ], 404);
    }

    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/',
    ]);

    // Update department details
    $desgination->update([
        'name' => $validated['name'],
    ]);

    // Return success response
    return response()->json([
        'message' => 'Desgination updated successfully',
        'data' => $desgination
    ], 200);
}




    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
{
    // Find the department by ID
    $desgination = Desgination::find($id);

    // Check if department exists
    if (!$desgination) {
        return response()->json([
            'message' => 'Desgination not found'
        ], 404);
    }

    // Delete the department
    $desgination->delete();

    // Return success response
    return response()->json([
        'message' => 'Desgination deleted successfully'
    ], 200);
}


}
