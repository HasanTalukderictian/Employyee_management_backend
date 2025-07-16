<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Extend Authenticatable for login features
use Illuminate\Notifications\Notifiable;
use App\Models\Employee;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relationship with Employee model
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
