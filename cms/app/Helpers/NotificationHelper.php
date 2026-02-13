<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function create($title, $message, $type = 'info')
    {
        return Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }

    public static function employeeAdded($employeeName)
    {
        return self::create(
            'New Employee Added',
            "Employee {$employeeName} has been added to the system",
            'success'
        );
    }

    public static function interviewScheduled($candidateName, $date)
    {
        return self::create(
            'Interview Scheduled',
            "Interview scheduled for {$candidateName} on {$date}",
            'info'
        );
    }

    public static function jobOpening($jobTitle)
    {
        return self::create(
            'New Job Opening',
            "Job opening created for {$jobTitle}",
            'info'
        );
    }

    public static function ticketCreated($ticketTitle)
    {
        return self::create(
            'New Ticket',
            "New support ticket: {$ticketTitle}",
            'warning'
        );
    }

    public static function salaryGenerated($month)
    {
        return self::create(
            'Salary Generated',
            "Monthly salary for {$month} has been generated",
            'success'
        );
    }
}