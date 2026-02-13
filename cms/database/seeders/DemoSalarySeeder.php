<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\SalaryRecord;
use App\Models\Attendance;
use Carbon\Carbon;

class DemoSalarySeeder extends Seeder
{
    public function run()
    {
        // Find or create demo employee
        $employee = Employee::updateOrCreate(
            ['email' => 'rahul.demo@company.com'],
            [
                'first_name' => 'Rahul',
                'last_name' => 'Kumar',
                'phone' => '9876543210',
                'department' => 'IT',
                'job_title' => 'Software Developer',
                'shift' => 'Day',
                'current_ctc' => 600000.00, // 6 LPA
                'in_hand_salary' => 45000.00, // 45k per month
                'basic_salary' => 25000.00,
                'joining_date' => Carbon::now()->subMonths(6),
                'user_type' => 'employee',
                'is_approved' => true,
                'password' => bcrypt('password123')
            ]
        );

        // Create attendance records for current month (22 working days out of 30)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::now()->daysInMonth;
        
        // Clear existing attendance for this employee and month
        Attendance::where('employee_id', $employee->id)
            ->whereYear('attendance_date', $currentYear)
            ->whereMonth('attendance_date', $currentMonth)
            ->delete();
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            
            // Skip weekends for this demo
            if ($date->isWeekend()) {
                continue;
            }
            
            // Create attendance - 22 present days, 3 absent days
            $status = ($day <= 22) ? 'Present' : 'Absent';
            
            Attendance::create([
                'employee_id' => $employee->id,
                'attendance_date' => $date->format('Y-m-d'),
                'status' => $status,
                'in_time' => $status === 'Present' ? '09:00:00' : null,
                'out_time' => $status === 'Present' ? '18:00:00' : null,
                'shift' => 'Day'
            ]);
        }

        // Calculate proper salary breakdown using same logic as salary calculator
        $inHandSalary = 45000.00;
        $gross = $this->calculateGrossFromInHand($inHandSalary);
        $basic = $gross * 0.60;
        $hra = $gross * 0.40;
        
        // PF calculations with cap rule
        $pfBasic = ($basic >= 15000) ? 15000 : $basic;
        $employeePf = $pfBasic * 0.12;
        $employerPf = $pfBasic * 0.13;
        
        // ESIC calculations (only if Gross <= 21000)
        if ($gross <= 21000) {
            $employeeEsic = $gross * 0.0075;
            $employerEsic = $gross * 0.0325;
        } else {
            $employeeEsic = 0;
            $employerEsic = 0;
        }
        
        // CTC = Gross + Employer contributions
        $ctc = $gross + $employerPf + $employerEsic;
        
        // Update employee with calculated CTC
        $employee->update(['current_ctc' => $ctc]);

        // Create salary record with all breakdown fields
        SalaryRecord::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'month' => $currentMonth,
                'year' => $currentYear
            ],
            [
                'basic_salary' => $inHandSalary, // In-hand salary
                'working_days' => 22.0,
                'deduction' => 5500.00, // Deduction for 3 absent days
                'advance' => 5000.00, // Advance given
                'incentive' => 3000.00, // Performance incentive
                'employee_pf' => $employeePf,
                'employee_esi' => $employeeEsic,
                'employer_pf' => $employerPf,
                'employer_esi' => $employerEsic,
                'net_salary' => 39500.00, // 45000 - 5500 deduction
                'shift' => 'Day'
            ]
        );

        echo "Demo employee created successfully!\n";
        echo "Employee: Rahul Kumar\n";
        echo "In-Hand Salary: ₹" . number_format($inHandSalary, 2) . "\n";
        echo "Gross Salary: ₹" . number_format($gross, 2) . "\n";
        echo "Basic Salary: ₹" . number_format($basic, 2) . "\n";
        echo "HRA: ₹" . number_format($hra, 2) . "\n";
        echo "CTC: ₹" . number_format($ctc, 2) . "\n";
        echo "Working Days: 22/30\n";
        echo "Advance: ₹5,000\n";
        echo "Incentive: ₹3,000\n";
        echo "Employee PF: ₹" . number_format($employeePf, 2) . "\n";
        echo "Employee ESI: ₹" . number_format($employeeEsic, 2) . "\n";
        echo "Employer PF: ₹" . number_format($employerPf, 2) . "\n";
        echo "Employer ESI: ₹" . number_format($employerEsic, 2) . "\n";
        echo "Deduction: ₹5,500\n";
        echo "Net Salary: ₹39,500\n";
        echo "Final Take Home: ₹47,500 (39500 + 5000 + 3000)\n";
    }
    
    private function calculateGrossFromInHand($inHand)
    {
        // Iterative approach to find gross that results in desired in-hand
        $gross = $inHand;
        
        for ($i = 0; $i < 10; $i++) {
            $basic = $gross * 0.60;
            $pfBasic = ($basic >= 15000) ? 15000 : $basic;
            $employeePf = $pfBasic * 0.12;
            
            $employeeEsic = ($gross <= 21000) ? $gross * 0.0075 : 0;
            
            $calculatedInHand = $gross - $employeePf - $employeeEsic;
            
            if (abs($calculatedInHand - $inHand) < 0.01) {
                break;
            }
            
            $gross = $gross + ($inHand - $calculatedInHand);
        }
        
        return round($gross, 2);
    }
}