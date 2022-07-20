<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = "departments";

    public function lecturers(){
         return $this->hasMany(Lecturer::class);
    }

    public function courses(){
        return $this->hasMany(Course::class);
    }
}
