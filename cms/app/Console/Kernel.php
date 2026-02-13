<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GenerateMonthlySalary::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run on 1st day of every month at 9:00 AM
        $schedule->command('salary:generate-monthly')
                 ->monthlyOn(1, '09:00')
                 ->withoutOverlapping();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}