<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens; // ✅ Add this
use Illuminate\Notifications\Notifiable;

class UsersModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ Include HasApiTokens here

    protected $table = 'userall';

    protected $fillable = [
        'employee_id',
        'email',
        'password',
         'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
