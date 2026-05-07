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



// public function index()
//     {
//         // sum approved days per employee
//         $takenByEmployee = LeaveRequest::select('employee_id', DB::raw('COALESCE(SUM(days),0) as taken'))
//             ->where('status', 'approved')
//             ->groupBy('employee_id')
//             ->pluck('taken','employee_id');

//         $balances = EmployeeLeave::with('employee')->get()->map(function($row) use ($takenByEmployee) {
//             $taken = (int) ($takenByEmployee[$row->employee_id] ?? 0);
//             return [
//                 'employee_id'     => $row->employee_id,
//                 'employee'        => $row->employee, // {first_name,last_name,...}
//                 'total_leave'     => (int) $row->total_leave,
//                 'taken_leave'     => $taken,
//                 'remaining_leave' => max(0, (int)$row->total_leave - $taken),

//             ];
//         });

//         return response()->json($balances);
//     }


public function index()
{
    // ১. প্রতিটি এমপ্লয়ীর কতদিন ছুটি নিয়েছে এবং তাদের সর্বশেষ ছুটির কারণ কি ছিল তা বের করা
    $employeeStats = LeaveRequest::select('employee_id',
            DB::raw('COALESCE(SUM(days), 0) as taken'),
            DB::raw('MAX(reason) as last_reason') // এখানে আমরা সর্বশেষ বা একটি কারণ নিচ্ছি
        )
        ->where('status', 'approved')
        ->groupBy('employee_id')
        ->get()
        ->keyBy('employee_id');

    // ২. ব্যালেন্স টেবিল থেকে ডাটা নিয়ে ম্যাপ করা
    $balances = EmployeeLeave::with('employee')->get()->map(function($row) use ($employeeStats) {

        $stats = $employeeStats[$row->employee_id] ?? null;
        $taken = (int) ($stats ? $stats->taken : 0);
        $reason = $stats ? $stats->last_reason : 'No leave taken yet';

        return [
            'employee_id'     => $row->employee_id,
            'employee'        => $row->employee,
            'total_leave'     => (int) $row->total_leave,
            'taken_leave'     => $taken,
            'remaining_leave' => max(0, (int)$row->total_leave - $taken),
            'reason'          => $reason, // এখানে reason রিটার্ন করা হলো
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

