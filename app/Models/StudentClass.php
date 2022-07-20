<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    use HasFactory;

    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function academic_year(){
        return $this->belongsTo(AcademicYear::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
