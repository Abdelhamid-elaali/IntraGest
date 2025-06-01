<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\NotificationService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Check stock levels daily at 8 AM
        $schedule->command('notifications:check-stock')
            ->dailyAt('08:00')
            ->name('check_stock_levels')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/stock-notifications.log'));

        // Check payment status daily at 9 AM
        $schedule->command('notifications:check-payments')
            ->dailyAt('09:00')
            ->name('check_payments')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/payment-notifications.log'));

        // Check student absences daily at 10 AM
        $schedule->command('notifications:check-absences')
            ->dailyAt('10:00')
            ->name('check_absences')
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/absence-notifications.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
