<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'working_days',
        'deduction',
        'advance',
        'incentive',
        'employee_pf',
        'employee_esi',
        'employer_pf',
        'employer_esi',
        'net_salary',
        'shift'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'working_days' => 'decimal:2',
        'deduction' => 'decimal:2',
        'advance' => 'decimal:2',
        'incentive' => 'decimal:2',
        'employee_pf' => 'decimal:2',
        'employee_esi' => 'decimal:2',
        'employer_pf' => 'decimal:2',
        'employer_esi' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}