<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckAbsences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-absences';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check student absences and send alerts for repeated or unjustified absences';

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
        $this->info('Checking student absences...');
        
        $count = $this->notificationService->checkStudentAbsences();
        
        $this->info("Absence check completed. {$count} alerts sent for students with repeated or unjustified absences.");
        
        return 0;
    }
}
