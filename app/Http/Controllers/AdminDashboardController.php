<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Desgination;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Salary;
use App\Models\Task;
use App\Models\UsersModel;

class AdminDashboardController extends Controller
{
    //

    public function index()
    {
        // Fetch all data from the models
        $attendance = Attendance::all();
        $department = Department::all();
        $desgination = Desgination::all();
        $employee = Employee::all();
        $leave = LeaveRequest::all();
        $salary = Salary::all();
        $usersmodel = UsersModel::all();
        $task = Task::all();

        // Return the data as a JSON response
        return response()->json([
            'attendance' => $attendance,
            'department' => $department,
            'desgination' => $desgination,
            'employee' => $employee,
            'leave' => $leave,
            'salary' => $salary,
            'usersmodel' => $usersmodel,
            'task' => $task
        ]);
    }
}
