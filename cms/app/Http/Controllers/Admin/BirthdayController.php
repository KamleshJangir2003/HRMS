<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Carbon\Carbon;

class BirthdayController extends Controller
{
    public function index()
    {
        // Today's birthdays
        $todayBirthdays = Employee::whereRaw('DATE_FORMAT(dob, "%m-%d") = ?', [date('m-d')])
            ->where('dob', '!=', null)
            ->get();

        // All employees with birthdays grouped by month
        $employees = Employee::whereNotNull('dob')
            ->orderByRaw('MONTH(dob), DAY(dob)')
            ->get();

        $birthdaysByMonth = $employees->groupBy(function($employee) {
            return Carbon::parse($employee->dob)->format('F');
        });

        return view('auth.admin.birthdays.index', compact('todayBirthdays', 'birthdaysByMonth'));
    }
}