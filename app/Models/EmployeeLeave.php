<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;

     protected $table = 'employee_leave';

    protected $fillable = [
        'employee_id',
        'total_leave',
        'taken_leave',
        'remaining_leave',
        'leave_type',
    ];

    // Relationship: One employee can have many leave records
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
