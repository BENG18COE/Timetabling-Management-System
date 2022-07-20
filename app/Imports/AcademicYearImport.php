<?php

namespace App\Imports;

use App\Models\AcademicYear;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AcademicYearImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        $row = array_change_key_case($row, CASE_LOWER);
        $module = AcademicYear::where('represent', $row['represent'])->first();
        
        if (! Arr::hasAny($row, ["start","start_year","start_date", "end", "end_year", "end_date"])) {
            return null;
        }
        
        if ( $module ){
            return null;
        }else{

            return new AcademicYear([
                "represent" => $row['represent'],
                "start_year" => $row['start_year'],
                "end_year" => $row['end_year']
            ]);
        }
    }
}
