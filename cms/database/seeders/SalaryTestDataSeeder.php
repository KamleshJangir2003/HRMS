<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SalaryTestDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample employees with salary data
        $employees = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@company.com',
                'phone' => '9876543210',
                'department' => 'IT',
                'job_title' => 'Software Developer',
                'basic_salary' => 50000.00,
                'shift' => 'Day',
                'user_type' => 'employee',
                'is_approved' => true,
                'password' => Hash::make('password123'),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@company.com',
                'phone' => '9876543211',
                'department' => 'HR',
                'job_title' => 'HR Executive',
                'basic_salary' => 45000.00,
                'shift' => 'Day',
                'user_type' => 'employee',
                'is_approved' => true,
                'password' => Hash::make('password123'),
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@company.com',
                'phone' => '9876543212',
                'department' => 'IT',
                'job_title' => 'Senior Developer',
                'basic_salary' => 65000.00,
                'shift' => 'Night',
                'user_type' => 'employee',
                'is_approved' => true,
                'password' => Hash::make('password123'),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'email' => 'sarah.wilson@company.com',
                'phone' => '9876543213',
                'department' => 'Finance',
                'job_title' => 'Accountant',
                'basic_salary' => 40000.00,
                'shift' => 'Day',
                'user_type' => 'employee',
                'is_approved' => true,
                'password' => Hash::make('password123'),
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Brown',
                'email' => 'david.brown@company.com',
                'phone' => '9876543214',
                'department' => 'Marketing',
                'job_title' => 'Marketing Executive',
                'basic_salary' => 42000.00,
                'shift' => 'Day',
                'user_type' => 'employee',
                'is_approved' => true,
                'password' => Hash::make('password123'),
            ]
        ];

        foreach ($employees as $employeeData) {
            $employee = Employee::create($employeeData);
            
            // Create sample attendance data for current month
            $this->createSampleAttendance($employee->id, $employeeData['shift']);
        }

        $this->command->info('Sample employees and attendance data created successfully!');
    }

    private function createSampleAttendance($employeeId, $shift)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $daysInMonth = Carbon::create($currentYear, $currentMonth)->daysInMonth;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($currentYear, $currentMonth, $day);
            
            // Skip future dates
            if ($date->isFuture()) {
                continue;
            }

            $status = 'Present';
            $dayOfWeek = $date->dayOfWeek;

            // Sunday is week off
            if ($dayOfWeek == 0) {
                $status = 'Week Off';
            } else {
                // Random attendance pattern
                $rand = rand(1, 100);
                if ($rand <= 85) {
                    $status = 'Present';
                } elseif ($rand <= 90) {
                    $status = 'Half Day';
                } elseif ($rand <= 95) {
                    $status = 'Paid Leave';
                } elseif ($rand <= 98) {
                    $status = 'Absent';
                } else {
                    $status = 'Unauthorized Leave';
                }
            }

            Attendance::create([
                'employee_id' => $employeeId,
                'attendance_date' => $date->format('Y-m-d'),
                'status' => $status,
                'shift' => $shift,
                'in_time' => $status == 'Present' ? '09:00:00' : null,
                'out_time' => $status == 'Present' ? '18:00:00' : null,
                'reason' => in_array($status, ['Absent', 'Unauthorized Leave']) ? 'Sample reason' : null,
            ]);
        }
    }
}