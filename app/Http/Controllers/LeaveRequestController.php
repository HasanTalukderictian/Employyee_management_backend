<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeaveRequestController extends Controller
{
    // =========================
    // APPLY LEAVE (SAFE)
    // =========================
    public function apply(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'leave_type'  => 'required|in:Paid,Unpaid',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:500',
        ]);

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);

        $leave = LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'leave_type'  => $request->leave_type,
            'start_date'  => $start->toDateString(),
            'end_date'    => $end->toDateString(),
            'days'        => $start->diffInDays($end) + 1,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'Leave application submitted!',
            'data'    => $leave
        ], 201);
    }

    // =========================
    // APPROVE (SAFE + OPTIMIZED)
    // =========================
    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::select('id','employee_id','status')
            ->findOrFail($id);

        $leave->update([
            'status'      => 'approved',
            'approved_by' => $request->approved_by,
            'approved_at' => now(),
            'remarks'     => $request->remarks,
        ]);

        Notification::create([
            'user_id' => $leave->employee_id,
            'title'   => 'Leave Approved',
            'message' => 'Your leave request has been approved.',
        ]);

        return response()->json([
            'message' => 'Leave approved',
        ]);
    }

    // =========================
    // REJECT (SAFE)
    // =========================
    public function reject(Request $request, $id)
    {
        $leave = LeaveRequest::select('id','employee_id')
            ->findOrFail($id);

        $leave->update([
            'status'      => 'rejected',
            'approved_by' => $request->approved_by,
            'approved_at' => now(),
            'remarks'     => $request->remarks,
        ]);

        Notification::create([
            'user_id' => $leave->employee_id,
            'title'   => 'Leave Rejected',
            'message' => 'Your leave request has been rejected.',
        ]);

        return response()->json([
            'message' => 'Leave rejected',
        ]);
    }

    // =========================
    // MY REQUESTS (PAGINATION)
    // =========================
    public function myRequests(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        $leaves = LeaveRequest::select('id','leave_type','start_date','end_date','status','created_at')
            ->where('employee_id', $request->employee_id)
            ->orderByDesc('id')
            ->paginate(20);

        return response()->json($leaves);
    }

    // =========================
    // ADMIN INDEX (CURSOR PAGINATION - BEST)
    // =========================
    public function index(Request $request)
    {
        $leaves = LeaveRequest::select('id','employee_id','leave_type','status','created_at', 'reason','start_date','end_date')
            ->with([
                'employee:id,first_name,last_name',
                'approver:id,name'
            ])
            ->orderBy('id', 'desc')
            ->cursorPaginate(20);

        return response()->json($leaves);
    }

    // =========================
    // UPDATE STATUS (SAFE)
    // =========================
    public function updateLeaveStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leave = LeaveRequest::select('id','employee_id')
            ->findOrFail($id);

        $leave->update([
            'status' => $request->status,
        ]);

        Notification::create([
            'user_id' => $leave->employee_id,
            'title'   => 'Leave Update',
            'message' => "Your leave request has been {$request->status}.",
        ]);

        return response()->json([
            'message' => 'Leave updated successfully'
        ]);
    }

    // =========================
    // MY LEAVES (CURSOR SAFE)
    // =========================
    public function myLeaves(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        $leaves = LeaveRequest::select('id','leave_type','start_date','end_date','status','created_at')
            ->where('employee_id', $request->employee_id)
            ->orderBy('id', 'desc')
            ->cursorPaginate(20);

        return response()->json($leaves);
    }
}
