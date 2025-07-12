<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveController extends Controller
{
    //

     public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'leave_type'  => 'required|in:Sick,Casual,Earned,Unpaid',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string',
            'status'      => 'required|in:Pending,Approved,Rejected',
        ]);

        // Create the leave record
        $leave = Leave::create($validatedData);

        // Return response
        return response()->json([
            'message' => 'Leave record created successfully',
            'data'    => $leave
        ], 201);
    }
}
