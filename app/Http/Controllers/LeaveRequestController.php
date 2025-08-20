<?php
namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeaveRequestController extends Controller
{
    // POST /api/apply-leave
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
        $days  = $start->diffInDays($end) + 1; // inclusive

        $req = LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'leave_type'  => $request->leave_type,
            'start_date'  => $start->toDateString(),
            'end_date'    => $end->toDateString(),
            'days'        => $days,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return response()->json(['message' => 'Leave application submitted!', 'data' => $req], 201);
    }

    // POST /api/leave-requests/{id}/approve
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status'      => 'approved',
            'approved_by' => $request->input('approved_by'), // admin employee_id
            'approved_at' => now(),
            'remarks'     => $request->input('remarks'),
        ]);

        return response()->json(['message' => 'Leave approved', 'data' => $leaveRequest]);
    }

    // POST /api/leave-requests/{id}/reject
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update([
            'status'      => 'rejected',
            'approved_by' => $request->input('approved_by'),
            'approved_at' => now(),
            'remarks'     => $request->input('remarks'),
        ]);

        return response()->json(['message' => 'Leave rejected', 'data' => $leaveRequest]);
    }

    // GET /api/my-leave-requests?employee_id=123
    public function myRequests(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employee,id']);
        $rows = LeaveRequest::where('employee_id', $request->employee_id)
                ->orderByDesc('created_at')->get();

        return response()->json($rows);
    }

    // GET /api/get-leaves
    public function index()
    {
        $leaves = LeaveRequest::with(['employee', 'approver'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($leaves);
    }
}


