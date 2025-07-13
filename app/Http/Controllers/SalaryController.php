<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;

class SalaryController extends Controller
{
    //

    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'employee_id'  => 'required|exists:employee,id',
            'month'        => 'required|string|max:20',
            'year'         => 'required|integer',
            'basic'        => 'required|numeric',
            'bonus'        => 'required|numeric',
            'deductions'   => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        // Create the salary record
        $salary = Salary::create($validatedData);

        // Return JSON response
        return response()->json([
            'message' => 'Salary record created successfully',
            'data'    => $salary
        ], 201);
    }

    public function getSalaryByEmployeeId($employee_id)
{
    // Fetch all salary records for the given employee_id
    $salaries = \App\Models\Salary::where('employee_id', $employee_id)->get();

    // Check if any salary records found
    if ($salaries->isEmpty()) {
        return response()->json([
            'message' => 'No salary records found for this employee.',
        ], 404);
    }

    // Return salary records
    return response()->json([
        'message' => 'Salary records retrieved successfully',
        'data' => $salaries
    ], 200);
}

}
