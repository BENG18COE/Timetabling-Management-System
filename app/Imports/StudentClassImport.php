<?php

namespace App\Imports;

use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Department;
use App\Models\StudentClass;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentClassImport implements ToModel,WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row) {
        $row = array_change_key_case($row, CASE_LOWER);
        $course = Course::where("name","LIKE", "%".$row['course']."%")->first();
        $dept = Department::where("name","LIKE", "%".$row['department']."%")->first();    

        $module = StudentClass::where('name', $row['name'])->first();

        $ay = AcademicYear::firstOrCreate([
            "represent" => $row['academic_year'],
            "start_year" => now(),
            "end_year" => now()->subYear()
        ]);
        
        if(! $dept){
            $dept = Department::create([
                "name" => $row['department']
            ]);
        }
        if(!$course){
            return null;
        }

        if( $module ){
            return null;
        }else {
            return new StudentClass([
                "name"  => $row['name'],
                "academic_year_id"  => $ay->id,
                "course_id"   => $course->id,
                "department_id" => $dept->id
            ]);
        }

    }
}
