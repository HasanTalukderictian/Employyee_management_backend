<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmployeeLeave;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeLeaveController extends Controller
{
    //



public function index()
    {
        // sum approved days per employee
        $takenByEmployee = LeaveRequest::select('employee_id', DB::raw('COALESCE(SUM(days),0) as taken'))
            ->where('status', 'approved')
            ->groupBy('employee_id')
            ->pluck('taken','employee_id');

        $balances = EmployeeLeave::with('employee')->get()->map(function($row) use ($takenByEmployee) {
            $taken = (int) ($takenByEmployee[$row->employee_id] ?? 0);
            return [
                'employee_id'     => $row->employee_id,
                'employee'        => $row->employee, // {first_name,last_name,...}
                'total_leave'     => (int) $row->total_leave,
                'taken_leave'     => $taken,
                'remaining_leave' => max(0, (int)$row->total_leave - $taken),
            ];
        });

        return response()->json($balances);
    }

    // POST /api/add-leaves  (admin set/update total)
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'total_leave' => 'required|integer|min:0',
        ]);

        $balance = EmployeeLeave::updateOrCreate(
            ['employee_id' => $request->employee_id],
            ['total_leave' => $request->total_leave]
        );

        return response()->json([
            'message' => 'Leave balance saved.',
            'data'    => $balance
        ]);
    }


}

