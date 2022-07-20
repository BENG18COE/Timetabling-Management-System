<?php

namespace App\Imports;

use App\Models\Lecturer;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LecturerImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row) {
        $uuid = $row['registration'] ?? $row['registration_number'] ?? $row['reg_no'] ?? $row['registration_id'] ?? $row['id'] ?? $row['registration_no'];

        $row = array_change_key_case($row, CASE_LOWER);
        
        if (! Arr::hasAny($row, ["name", "full_name", "first_name","lecturer_name"])) {
            return null;
        }

        $module = Lecturer::where('uuid', $uuid)->first();

        if ( $module ){
            return null;
        }else{
            return new Lecturer([
                "uuid" => $uuid,
                "name" => $row['name'] ?? $row['full_name'] ?? $row['lecturer_name'] ?? $row['first_name'],
                "email"  => $row['email'],
                "password" => bcrypt($uuid),
            ]);
        }
    }

    public function uniqueBy() {
        return ['email', 'uuid'];
    }
}
