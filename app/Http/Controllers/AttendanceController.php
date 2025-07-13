<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Store or update attendance record.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'date'        => 'required|date',
            'check_in'    => 'nullable|date_format:H:i:s',
            'check_out'   => 'nullable|date_format:H:i:s',
            'status'      => 'required|in:Present,Absent,Leave,Half-day',
        ]);

        // Check if attendance for the employee and date already exists
        $attendance = Attendance::where('employee_id', $validatedData['employee_id'])
            ->where('date', $validatedData['date'])
            ->first();

        if ($attendance) {
            // If exists, update check_out and status
            if (isset($validatedData['check_out'])) {
                $attendance->check_out = $validatedData['check_out'];
            }
            $attendance->status = $validatedData['status'];
            $attendance->save();

            $message = 'Attendance updated successfully';
        } else {
            // If not exists, create new record with check_in and status
            $attendance = Attendance::create($validatedData);
            $message = 'Attendance recorded successfully';
        }

        // Return response
        return response()->json([
            'message' => $message,
            'data' => $attendance
        ], 201);
    }



     public function index()
    {
        // Load attendance records with employee relationship
        $attendances = Attendance::with('employee')->get();

        // Return response
        return response()->json([
            'message' => 'All attendance records fetched successfully',
            'data' => $attendances
        ], 200);
    }



public function checkOut(Request $request)
{
    try {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'check_out' => 'required|date_format:H:i:s',
            'date' => 'required|date',
        ]);

        $attendance = Attendance::where('employee_id', $validatedData['employee_id'])
                                ->where('date', $validatedData['date'])
                                ->first();

        if (!$attendance) {
            return response()->json([
                'message' => 'Check-in record not found for this date. Please check-in first.'
            ], 404);
        }

        $attendance->check_out = $validatedData['check_out'];
        $attendance->save();

        return response()->json([
            'message' => 'Check-out time updated successfully.',
            'data' => $attendance
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred',
            'error' => $e->getMessage()
        ], 500);
    }
}



}
