<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\SalaryRecord;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GenerateMonthlySalary extends Command
{
    protected $signature = 'salary:generate-monthly {--month=} {--year=}';
    protected $description = 'Generate monthly salary for all employees based on attendance';

    public function handle()
    {
        $month = $this->option('month') ?: Carbon::now()->subMonth()->month;
        $year = $this->option('year') ?: Carbon::now()->subMonth()->year;
        
        $this->info("Generating salary for {$month}/{$year}...");
        
        // Check if salary already generated for this month
        $existingSalaries = SalaryRecord::where('month', $month)
            ->where('year', $year)
            ->count();
            
        if ($existingSalaries > 0) {
            $this->warn("Salary already generated for {$month}/{$year}. Skipping...");
            return;
        }
        
        $employees = Employee::where('user_type', 'employee')
            ->whereNotNull('in_hand_salary')
            ->where('in_hand_salary', '>', 0)
            ->get();

        if ($employees->isEmpty()) {
            $this->error('No employees found with valid salary information!');
            return;
        }

        $totalDaysInMonth = Carbon::create($year, $month)->daysInMonth;
        $generatedCount = 0;
        $skippedCount = 0;

        $this->info("Processing {$employees->count()} employees...");
        
        $progressBar = $this->output->createProgressBar($employees->count());
        $progressBar->start();

        foreach ($employees as $employee) {
            // Check if salary already exists for this employee
            $existingRecord = SalaryRecord::where('employee_id', $employee->id)
                ->where('month', $month)
                ->where('year', $year)
                ->first();
                
            if ($existingRecord) {
                $skippedCount++;
                $progressBar->advance();
                continue;
            }

            $inHandSalary = $employee->in_hand_salary;
            
            // Calculate salary components
            $gross = $this->calculateGrossFromInHand($inHandSalary);
            $basic = $gross * 0.60;
            
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

            $attendanceRecords = DB::table('attendance')
                ->where('employee_id', $employee->id)
                ->whereYear('attendance_date', $year)
                ->whereMonth('attendance_date', $month)
                ->get();

            $present = $attendanceRecords->where('status', 'Present')->count();
            $absent = $attendanceRecords->where('status', 'Absent')->count();
            $halfDay = $attendanceRecords->where('status', 'Half Day')->count();
            $unauthorizedLeave = $attendanceRecords->where('status', 'Unauthorized Leave')->count();
            $paidLeave = $attendanceRecords->where('status', 'Paid Leave')->count();
            $holiday = $attendanceRecords->where('status', 'Holiday')->count();
            $weekOff = $attendanceRecords->where('status', 'Week Off')->count();
            $compOff = $attendanceRecords->where('status', 'Comp Off')->count();

            // Calculate working days
            $workingDays = $present + $paidLeave + $compOff + ($halfDay * 0.5);

            // Calculate per day salary
            $perDaySalary = $inHandSalary / $totalDaysInMonth;

            // Calculate salary based on working days only (proportional)
            $earnedSalary = $workingDays * $perDaySalary;
            
            // No deductions - just pay for actual working days
            $deduction = $inHandSalary - $earnedSalary;
            $netSalary = $earnedSalary;

            SalaryRecord::create([
                'employee_id' => $employee->id,
                'month' => $month,
                'year' => $year,
                'basic_salary' => $inHandSalary,
                'working_days' => $workingDays,
                'deduction' => $deduction,
                'advance' => 0,
                'incentive' => 0,
                'employee_pf' => $employeePf,
                'employee_esi' => $employeeEsic,
                'employer_pf' => $employerPf,
                'employer_esi' => $employerEsic,
                'net_salary' => $netSalary,
                'shift' => $employee->shift ?? 'Day'
            ]);
            
            $generatedCount++;
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Salary generation completed!");
        $this->info("Generated: {$generatedCount} employees");
        if ($skippedCount > 0) {
            $this->warn("Skipped: {$skippedCount} employees (already existed)");
        }
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