<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Target;
use App\Models\Task;
use App\Models\TaskActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->has('employee_id')) {
            return Task::with('activities')
                ->where('employee_id', $request->employee_id)
                ->get();
        }

        // admin → return all
       return Task::with(['activities', 'employee'])->get();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'due_date'    => 'required|date',
            'status'      => 'required|in:Pending,Completed,Overdue',
            'employee_id' => 'required|exists:employee,id', // who is creating the task
        ]);

        $task = Task::create($validated);

        TaskActivity::create([
            'task_id'     => $task->id,
            'description' => 'Task Created',
            'employee_id' => $validated['employee_id'], // track creator
        ]);

        return response()->json($task->load(['activities.employee', 'employee']), 201);
    }



//     public function update(Request $request, $id)
// {
//     $task = Task::findOrFail($id);

//     $validated = $request->validate([
//         'title'       => 'sometimes|string|max:255',
//         'due_date'    => 'sometimes|date',
//         'status'      => 'sometimes|in:Pending,Completed,Overdue',
//         'employee_id' => 'required|exists:employee,id',
//     ]);

//     $oldStatus = $task->status; // 🔥 old status save করো

//     $task->update($validated);

//     // activity log
//     if ($request->has('status')) {
//         TaskActivity::create([
//             'task_id'     => $task->id,
//             'description' => 'Status changed to ' . $request->status,
//             'employee_id' => $validated['employee_id'],
//         ]);
//     }

//     // 🔥 TARGET UPDATE LOGIC
//     if ($oldStatus !== 'Completed' && $request->status === 'Completed') {

//     $currentMonth = Carbon::parse($task->due_date)->format('F');

//     $target = Target::where('employee_id', $task->employee_id)
//         ->where('month', $currentMonth)
//         ->first();

//     if ($target) {
//         $target->increment('achieved_value');
//     }
// }

//     return response()->json($task->load(['activities.employee', 'employee']));
// }




public function update(Request $request, $id)
{
    $task = Task::findOrFail($id);

    $validated = $request->validate([
        'title'       => 'sometimes|string|max:255',
        'due_date'    => 'sometimes|date',
        'status'      => 'sometimes|in:Pending,Completed,Overdue',
        'employee_id' => 'required|exists:employee,id',
    ]);

    $oldStatus = $task->status;

    // টাস্ক আপডেট করুন
    $task->update($validated);

    // Activity Log তৈরি
    if ($request->has('status')) {
        TaskActivity::create([
            'task_id'     => $task->id,
            'description' => 'Status changed to ' . $request->status,
            'employee_id' => $validated['employee_id'],
        ]);
    }

    // 🔥 TARGET UPDATE LOGIC
    // আপনি যদি চান যে প্রতিবার কমপ্লিট স্ট্যাটাস পাঠালেই ভ্যালু বাড়বে, তবে $oldStatus চেকটি সরিয়ে দিন।
    // অথবা নিশ্চিত করুন আগের স্ট্যাটাসটি 'Pending' বা 'Overdue' ছিল।
    if ($oldStatus !== 'Completed' && $request->status === 'Completed') {

        // আপনার JSON অনুযায়ী due_date হলো "2026-04-23"
        // format('F') দিবে "April"
        $currentMonth = \Carbon\Carbon::parse($task->due_date)->format('F');

        $target = \App\Models\Target::where('employee_id', $task->employee_id)
            ->where('month', 'LIKE', $currentMonth . '%') // এটি "April" বা "Apr" দুইটাই হ্যান্ডেল করবে
            ->first();

        if ($target) {
            $target->increment('achieved_value');
        } else {
            // টার্গেট না পেলে লগ চেক করুন কেন পাচ্ছে না
            \Log::info("Target not found for Employee: {$task->employee_id} in Month: {$currentMonth}");
        }
    }

    return response()->json($task->load(['activities.employee', 'employee']));
}

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
