<?php

namespace App\Imports;

use App\Models\Setting;

use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SettingImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row) {
        if (!isset($row["name"])) {
            return null;
        }

        return new Setting([
            "name"  => $row['name'] ?? $row["Name"],
            "value"  => $row['value'],
        ]);
    }

}
