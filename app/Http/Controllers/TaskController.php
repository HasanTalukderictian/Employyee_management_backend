<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskActivity;
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


    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'due_date'    => 'sometimes|date',
            'status'      => 'sometimes|in:Pending,Completed,Overdue',
            'employee_id' => 'required|exists:employee,id', // who is updating
        ]);

        $task->update($validated);

        if ($request->has('status')) {
            TaskActivity::create([
                'task_id'     => $task->id,
                'description' => 'Status changed to ' . $request->status,
                'employee_id' => $validated['employee_id'],
            ]);
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
