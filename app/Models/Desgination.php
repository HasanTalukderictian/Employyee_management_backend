<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desgination extends Model
{
    use HasFactory;

    protected $table ='desgination';

    protected $fillable = [
        'title'
    ];
}
