<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel,WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row) {
        $reg = $row['registration'] ?? $row['registration_number'] ?? $row['reg_no'] ?? $row['registration_id'] ?? $row['id'] ?? $row['registration_no'];
        
        $row = array_change_key_case($row, CASE_LOWER);
        
        if (! Arr::hasAny($row, ["name", "full_name", "first_name","student_name"])) {
            return null;
        }

        $module = Student::where('registration_id', $reg)->first();

        if ( $module ){
            return null;
        }else{
            return new Student([
                "name"  => $row['name'] ?? $row['full_name'] ?? $row['student_name'] ?? $row['first_name'],
                "email"  => $row['email'],
                "password" => bcrypt($reg),
                "registration_id"   => $reg,
            ]);
        }
    }

    public function uniqueBy() {
        return ['email', 'registration_id'];
    }
}
