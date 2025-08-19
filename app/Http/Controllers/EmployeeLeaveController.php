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

 public function apply(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'leave_type'  => 'required|in:Paid,Unpaid',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:500',
        ]);

        $leave = EmployeeLeave::create([
            'employee_id'     => $request->employee_id,
            'total_leave'     => 0, // optional, can calculate later
            'taken_leave'     => 0,
            'remaining_leave' => 0,
            'leave_type'      => $request->leave_type,
            'start_date'      => $request->start_date,
            'end_date'        => $request->end_date,
            'reason'          => $request->reason,
        ]);

        return response()->json([
            'message' => 'Leave application submitted successfully!',
            'data'    => $leave
        ], 201);
    }

}
