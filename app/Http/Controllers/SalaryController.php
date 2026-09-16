<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;

class SalaryController extends Controller
{
    /**
     * Store a new salary record
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id'  => 'required|exists:employee,id',
            'month'        => 'required|string|max:20',
            'year'         => 'required|integer',
            'basic'        => 'required|numeric',
            'bonus'        => 'required|numeric',
            'deductions'   => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        $exists = Salary::where('employee_id', $validatedData['employee_id'])
            ->where('month', $validatedData['month'])
            ->where('year', $validatedData['year'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Salary for this employee for the specified month and year already exists.'
            ], 422);
        }

        $salary = Salary::create($validatedData);

        // 🎯 Load relationships for return
        $salary->load(['employee.designation', 'employee.department']);

        return response()->json([
            'message' => 'Salary record created successfully',
            'data'    => $salary
        ], 201);
    }

    /**
     * Get salary by employee ID
     */
    public function getSalaryByEmployeeId($employee_id)
    {
        $salaries = Salary::with(['employee.designation', 'employee.department'])
            ->where('employee_id', $employee_id)
            ->get();

        if ($salaries->isEmpty()) {
            return response()->json([
                'message' => 'No salary records found for this employee.',
            ], 404);
        }

        return response()->json([
            'message' => 'Salary records retrieved successfully',
            'data'    => $salaries
        ], 200);
    }

    /**
     * 🎯 List all salaries with employee + designation + department
     */
    public function index(Request $request)
    {
        // 🎯 Eager load deeply nested relationships
        $query = Salary::with([
            'employee.designation',
            'employee.department',
        ]);

        // Search by employee name (first_name or last_name)
        if ($request->has('search') && !empty($request->input('search'))) {
            $searchTerm = $request->input('search');

            $query->whereHas('employee', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
            });
        }

        $salaries = $query->orderBy('id', 'desc')->get();

        return response()->json([
            'message' => 'Salaries retrieved successfully',
            'data'    => $salaries
        ]);
    }

    /**
     * Delete a salary record
     */
    public function destroy($id)
    {
        $salary = Salary::find($id);

        if (!$salary) {
            return response()->json([
                'message' => 'Salary record not found.',
            ], 404);
        }

        $salary->delete();

        return response()->json([
            'message' => 'Salary record deleted successfully.',
        ], 200);
    }
}
