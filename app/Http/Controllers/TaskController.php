<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskActivity;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    //

    public function index()
    {
        return Task::with('activities')->orderBy('id', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'    => 'required|string|max:255',
            'due_date' => 'required|date',
            'status'   => 'required|in:Pending,Completed,Overdue',
        ]);

        $task = Task::create($validated);

        TaskActivity::create([
            'task_id'    => $task->id,
            'description'=> 'Task Created',
        ]);

        return response()->json($task->load('activities'), 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title'    => 'sometimes|string|max:255',
            'due_date' => 'sometimes|date',
            'status'   => 'sometimes|in:Pending,Completed,Overdue',
        ]);

        $task->update($validated);

        if ($request->has('status')) {
            TaskActivity::create([
                'task_id'    => $task->id,
                'description'=> 'Status changed to ' . $request->status,
            ]);
        }

        return response()->json($task->load('activities'));
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
