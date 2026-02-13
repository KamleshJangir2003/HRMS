<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $notifications = [
            [
                'title' => 'New Employee Added',
                'message' => 'John Doe has been added to the system',
                'type' => 'success',
                'is_read' => false
            ],
            [
                'title' => 'Interview Scheduled',
                'message' => 'Interview scheduled for Jane Smith tomorrow at 2 PM',
                'type' => 'info',
                'is_read' => false
            ],
            [
                'title' => 'New Support Ticket',
                'message' => 'Employee reported login issues - Ticket #1234',
                'type' => 'warning',
                'is_read' => false
            ]
        ];

        foreach ($notifications as $notification) {
            Notification::create($notification);
        }
    }
}