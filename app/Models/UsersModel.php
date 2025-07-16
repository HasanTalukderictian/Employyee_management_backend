<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class UsersModel extends Model
{
    use HasFactory, HasApiTokens;


    protected $table ='users';

    protected $fillable = [
        'employee_id', 'email', 'password'
    ];
}
