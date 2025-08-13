<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    //



     public function index()
    {
        // Retrieve all leave records with related employee info
        $leaves = EmployeeLeave::with('employee')->get();
        return response()->json($leaves);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employee,id',
        'total_leave' => 'required|integer|min:0',
        'taken_leave' => 'nullable|integer|min:0',
        'leave_type'  => 'nullable|in:Paid,Unpaid'
    ]);

    $taken_leave = $request->taken_leave ?? 0; // Default to 0 if null
    $leave_type  = $request->leave_type ?? 'Paid'; // Default to Paid

    $remaining_leave = $request->total_leave - $taken_leave;

    $leave = EmployeeLeave::create([
        'employee_id'      => $request->employee_id,
        'total_leave'      => $request->total_leave,
        'taken_leave'      => $taken_leave,
        'remaining_leave'  => $remaining_leave,
        'leave_type'       => $leave_type
    ]);

    return response()->json([
        'message' => 'Leave record created successfully!',
        'data'    => $leave
    ], 201);
}

}
