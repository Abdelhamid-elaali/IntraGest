<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check payment statuses and send reminders for upcoming and overdue payments';

    /**
     * The notification service instance.
     *
     * @var \App\Services\NotificationService
     */
    protected $notificationService;

    /**
     * Create a new command instance.
     *
     * @param  \App\Services\NotificationService  $notificationService
     * @return void
     */
    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking payment statuses...');
        
        $count = $this->notificationService->checkAllPayments();
        
        $this->info("Payment check completed. {$count} reminders sent for upcoming and overdue payments.");
        
        return 0;
    }
}
