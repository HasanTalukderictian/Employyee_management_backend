<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function scanAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employee,id',
            'qr_token' => 'required'
        ]);

        $today = Carbon::today()->toDateString();
        $currentTime = Carbon::now();

        // ১. QR Token ভ্যালিডেশন (আজকের তারিখের সাথে টোকেন মিলছে কি না)
        $expectedToken = "ATTENDANCE_" . $today;
        if ($request->qr_token !== $expectedToken) {
            return response()->json(['message' => 'Invalid or Expired QR Code'], 403);
        }

        // ২. চেক করা এমপ্লয়ি আজ অলরেডি চেক-ইন করেছে কি না
        $attendance = Attendance::where('employee_id', $request->employee_id)
                                ->where('attendance_date', $today)
                                ->first();

        if ($attendance) {
            return response()->json(['message' => 'Attendance already marked for today'], 400);
        }

        // ৩. লেট কাউন্ট লজিক (ঐচ্ছিক: ধরুন সকাল ১০টার পর লেট)
        $status = 'Present';
        if ($currentTime->gt(Carbon::createFromTimeString('10:00:00'))) {
            $status = 'Late';
        }

        // ৪. ডাটা সেভ করা
        Attendance::create([
            'employee_id' => $request->employee_id,
            'attendance_date' => $today,
            'check_in' => $currentTime->toTimeString(),
            'status' => $status,
            'location_coords' => $request->location_coords ?? null,
        ]);

        return response()->json([
            'message' => 'Attendance successful!',
            'status' => $status,
            'time' => $currentTime->format('h:i A')
        ], 200);
    }
}
