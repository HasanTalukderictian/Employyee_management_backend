<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = ['employee_id', 'attendance_date', 'check_in', 'check_out', 'status', 'location_coords'];



    public function user()
    {
        return $this->belongsTo(Employee::class);
    }
}
