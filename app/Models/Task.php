<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = ['title', 'due_date', 'status'];

    public function activities()
    {
        return $this->hasMany(TaskActivity::class);
    }
}
