<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;


    protected $table ='employee';

     protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'department_id',
        'designation_id',
        'hire_date',
        'profile_picture',
        'status',
    ];

    /**
     * Define relationship with Department.
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Define relationship with Designation.
     */
    public function designation()
    {
        return $this->belongsTo(Desgination::class, 'designation_id');
    }
}
