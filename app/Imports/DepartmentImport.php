<?php

namespace App\Imports;

use App\Models\Department;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
   
    public function model(array $row)
    {
        $name = $row['name'] ?? $row['department_name'] ?? $row['dept_name'] ;

        $row = array_change_key_case($row, CASE_LOWER);
        
        if (! Arr::hasAny($row, ["name", "department_name", "dept_name"])) {
            return null;
        }

        $module = Department::where('name', $name)->first();

        if ( $module ){
            return null;
        }else{
            return new Department([
                "name" => $name,
            ]);
        }
    }
}
