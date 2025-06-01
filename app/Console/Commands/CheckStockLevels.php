<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;

class CheckStockLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check stock levels and send notifications for low stock items';

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
        $this->info('Checking stock levels...');
        
        $count = $this->notificationService->checkAllStockLevels();
        
        $this->info("Stock level check completed. {$count} notifications sent for low stock items.");
        
        return 0;
    }
}
