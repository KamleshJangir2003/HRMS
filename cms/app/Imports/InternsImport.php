<?php

namespace App\Imports;

use App\Models\Intern;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class InternsImport implements ToModel, WithHeadingRow
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function model(array $row)
    {
        return new Intern([
            'number' => $row['number'] ?? $row[0],
            'name' => $row['name'] ?? $row[1],
            'role' => $this->role,
            'status' => 'new',
            'condition_status' => '',
            'final_result' => 'Pending'
        ]);
    }
}