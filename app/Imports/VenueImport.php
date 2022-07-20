<?php

namespace App\Imports;

use App\Models\Venue;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VenueImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row) {
        $name = $row['name'] ?? $row['venue_name'] ?? $row['room_name'];
        
        $row = array_change_key_case($row, CASE_LOWER);
        
        if (! Arr::hasAny($row, ["name", "venue_number", "venue_name"])) {
            return null;
        }

        $module = Venue::where('name', $name)->first();

        if (!isset($row["name"])) {
            return null;
        }
        if ( $module ){
            return null;
        }else{
            return new Venue([
                "name"  => $name,
                "capacity"  => $row['capacity'],
                "type"   => $row['type'],
            ]);
        }
    }

    public function uniqueBy() {
        return 'name';
    }
}
