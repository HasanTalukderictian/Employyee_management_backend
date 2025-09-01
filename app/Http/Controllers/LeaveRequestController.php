<?php
namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LeaveRequestController extends Controller
{
    // POST /api/apply-leave
    public function apply(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id', // table name check
            'leave_type'  => 'required|in:Paid,Unpaid',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'reason'      => 'required|string|max:500',
        ]);

        $start = Carbon::parse($request->start_date);
        $end   = Carbon::parse($request->end_date);
        $days  = $start->diffInDays($end) + 1;

        $leave = LeaveRequest::create([
            'employee_id' => $request->employee_id,
            'leave_type'  => $request->leave_type,
            'start_date'  => $start->toDateString(),
            'end_date'    => $end->toDateString(),
            'days'        => $days,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return response()->json(['message' => 'Leave application submitted!', 'data' => $leave], 201);
    }

    // POST /api/leave-requests/{id}/approve
    public function approve(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status'      => 'approved',
            'approved_by' => $request->input('approved_by'),
            'approved_at' => now(),
            'remarks'     => $request->input('remarks'),
        ]);

        Notification::create([
            'user_id' => $leave->employee_id,
            'title'   => 'Leave Approved',
            'message' => 'Your leave request has been approved.',
        ]);

        return response()->json(['message' => 'Leave approved', 'data' => $leave]);
    }

    // POST /api/leave-requests/{id}/reject
    public function reject(Request $request, $id)
    {
        $leave = LeaveRequest::findOrFail($id);

        $leave->update([
            'status'      => 'rejected',
            'approved_by' => $request->input('approved_by'),
            'approved_at' => now(),
            'remarks'     => $request->input('remarks'),
        ]);

        Notification::create([
            'user_id' => $leave->employee_id,
            'title'   => 'Leave Rejected',
            'message' => 'Your leave request has been rejected.',
        ]);

        return response()->json(['message' => 'Leave rejected', 'data' => $leave]);
    }

    // GET /api/my-leave-requests?employee_id=123
    public function myRequests(Request $request)
    {
        $request->validate(['employee_id' => 'required|exists:employees,id']);

        $leaves = LeaveRequest::where('employee_id', $request->employee_id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($leaves);
    }

    // GET /api/get-leaves
    public function index()
    {
        $leaves = LeaveRequest::with(['employee', 'approver'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($leaves);
    }

    // Update leave status and notify user
    public function updateLeaveStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $leave = LeaveRequest::findOrFail($id);
        $leave->status = $request->status;
        $leave->save();

        Notification::create([
            'user_id' => $leave->employee_id,
            'title'   => 'Leave Request Update',
            'message' => "Your leave request has been {$request->status}.",
        ]);

        return response()->json(['message' => 'Leave updated and user notified']);
    }
}
