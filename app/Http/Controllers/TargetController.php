<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Target;
use App\Models\Task;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    //

  public function store(Request $request)
{
    $request->validate([
        'employee_id' => 'required|exists:employee,id',
        'month' => 'required',
        'target_value' => 'required|integer'
    ]);

    // 🔴 check duplicate
    $exists = Target::where('employee_id', $request->employee_id)
        ->where('month', $request->month)
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'This employee already has a target for this month'
        ], 409); // conflict
    }

    $target = Target::create([
        'employee_id' => $request->employee_id,
        'month' => $request->month,
        'target_value' => $request->target_value,
        'achieved_value' => 0
    ]);

    return response()->json([
        'message' => 'Target created successfully',
        'data' => $target
    ]);
}

    // Get all targets
    public function index()
    {
        $targets = Target::with('employee')->get();

        return response()->json([
            'message' => 'All targets',
            'data' => $targets
        ]);
    }

    // Sync achieved tasks (manual or cron call)
    public function syncAchievement($employeeId, $month)
    {
        $completedTasks = Task::where('employee_id', $employeeId)
            ->where('status', 'Completed')
            ->count();

        $target = Target::where('employee_id', $employeeId)
            ->where('month', $month)
            ->first();

        if ($target) {
            $target->update([
                'achieved_value' => $completedTasks
            ]);
        }

        return response()->json([
            'message' => 'Target updated',
            'data' => $target
        ]);
    }

      public function updateTargetProgress($employeeId, $month)
    {
        $completedTasks = Task::where('employee_id', $employeeId)
            ->where('status', 'Completed')
            ->count();

        $target = Target::where('employee_id', $employeeId)
            ->where('month', $month)
            ->first();

        if ($target) {
            $target->update([
                'achieved_value' => $completedTasks
            ]);
        }

        return response()->json([
            'message' => 'Target progress updated successfully',
            'data' => $target
        ]);
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'employee_id' => 'required|exists:employee,id',
        'month' => 'required',
        'target_value' => 'required|integer'
    ]);

    // Target খুঁজে বের করা
    $target = Target::find($id);

    if (!$target) {
        return response()->json([
            'message' => 'Target not found'
        ], 404);
    }

    // Update data
    $target->update([
        'employee_id' => $request->employee_id,
        'month' => $request->month,
        'target_value' => $request->target_value,
    ]);

    return response()->json([
        'message' => 'Target updated successfully',
        'data' => $target
    ]);
}

public function destroy($id)
{
    // Target খুঁজে বের করা
    $target = Target::find($id);

    if (!$target) {
        return response()->json([
            'message' => 'Target not found'
        ], 404);
    }

    // Delete করা
    $target->delete();

    return response()->json([
        'message' => 'Target deleted successfully'
    ]);
}


}
