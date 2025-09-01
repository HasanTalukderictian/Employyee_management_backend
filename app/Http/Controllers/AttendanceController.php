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
    $validatedData = $request->validate([
        'employee_id' => 'nullable|exists:employee,id',
        'user_id'     => 'nullable|exists:users,id',
        'date'        => 'required|date',
        'check_in'    => 'nullable|date_format:H:i:s',
        'check_out'   => 'nullable|date_format:H:i:s',
    ]);

    if (empty($validatedData['employee_id']) && empty($validatedData['user_id'])) {
        return response()->json(['error' => 'Either employee_id or user_id is required'], 422);
    }

    // Only set check_in if not already set for today
    $attendance = Attendance::firstOrNew([
        'employee_id' => $validatedData['employee_id'] ?? null,
        'user_id'     => $validatedData['user_id'] ?? null,
        'date'        => $validatedData['date'],
    ]);

    if (!$attendance->exists && isset($validatedData['check_in'])) {
        $attendance->check_in = $validatedData['check_in'];
    }

    if (isset($validatedData['check_out'])) {
        $attendance->check_out = $validatedData['check_out'];
    }

    $attendance->save();

    return response()->json([
        'message' => 'Attendance recorded successfully',
        'data' => $attendance
    ], 201);
}



public function index()
{
    $attendances = Attendance::with(['employee', 'user'])->get();

    return response()->json([
        'message' => 'All attendance records fetched successfully',
        'data' => $attendances
    ], 200);
}




public function checkOut(Request $request)
{
    $validatedData = $request->validate([
        'employee_id' => 'nullable|exists:employee,id',
        'user_id'     => 'nullable|exists:userall,id',
        'date'        => 'required|date',
        'check_out'   => 'required|date_format:H:i:s',
    ]);

    $attendance = Attendance::where(function($query) use ($validatedData) {
                                if (!empty($validatedData['employee_id'])) {
                                    $query->where('employee_id', $validatedData['employee_id']);
                                }
                                if (!empty($validatedData['user_id'])) {
                                    $query->where('user_id', $validatedData['user_id']);
                                }
                            })
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
}




}
