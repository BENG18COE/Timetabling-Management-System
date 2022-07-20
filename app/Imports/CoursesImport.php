<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class CoursesImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithUpserts
{

    public function model(array $row) {
        $row = array_change_key_case($row, CASE_LOWER);
        $course = Course::where('code', $row['code'])->first();
        $dept = Department::where("name","LIKE", "%".$row["department"]."%")->first();
        
        if (!isset($row["name"])) {
            return null;
        }
        if(! $dept){
            $dept = Department::create([
                "name" => $row['department']
            ]);
        }
        if ( $course ){
            return null;
        }else{
            return new Course([
                "name"  => $row['name'],
                "code"  => $row['code'],
                "program"   => $row['program'],
                "field" => $row['field'],
                "department_id" => $dept->id
            ]);
        }
    }

    public function uniqueBy() {
        return 'code';
    }
}
