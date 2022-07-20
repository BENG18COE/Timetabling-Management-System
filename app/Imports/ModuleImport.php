<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Module;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ModuleImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row) {
        $row = array_change_key_case($row, CASE_LOWER);
        $module = Module::where('code', $row['code'])->first();
        $dept = Department::where("name","LIKE", "%".$row["department"]."%")->first();
        $lecturer = Lecturer::where("name","LIKE", "%".$row["lecturer"]."%")->first();
        
        if(! $dept){
            $dept = Department::create([
                "name" => $row['department']
            ]);
        }
        if(!$lecturer){
            return null;
        }
        
        if (! Arr::hasAny($row, ["name"])) {
            return null;
        }
        
        if ( $module ){
            return null;
        }else{
            return new Module([
                "name"  => $row['name'],
                "code"  => $row['code'],
                "type"   => $row['type'],
                "credits" => $row['credits'],
                "capacity" => $row['number_of_students'],
                "department_id" => $dept->id,
                "lecturer_id" => $lecturer->id,
            ]);
        }
    }

    public function uniqueBy() {
        return 'code';
    }
}
