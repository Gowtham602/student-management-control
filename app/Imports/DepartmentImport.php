<?php
namespace App\Imports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Department([
            'name' => $row['name'],
            'code' => $row['code'],
        ]);
    }
}
