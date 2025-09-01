<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskActivity extends Model
{
    use HasFactory;

    protected $table = 'task_activities';

    protected $fillable = ['task_id', 'description', 'logged_at', 'employee_id'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id'); // Or User if you’re using users table
    }
}
