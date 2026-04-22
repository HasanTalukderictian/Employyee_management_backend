<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'target_value',
        'achieved_value'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
