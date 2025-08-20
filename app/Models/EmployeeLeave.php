<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;

    

    protected $table = 'employee_leave';
    public $incrementing = false; //
    protected $primaryKey = 'employee_id';
    protected $fillable = ['employee_id', 'total_leave'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
