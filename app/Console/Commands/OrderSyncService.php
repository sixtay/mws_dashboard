<?php

namespace App\Console\Commands;

use App\Services\Trevco\AmazonReportTransformer;
use App\Services\Trevco\AmazonSync\AmazonOrderSyncService;
use App\Services\Trevco\AmazonSync\AmazonReportModelSync;
use Illuminate\Console\Command;

class OrderSyncService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trevco:sync-orders {days_past=1 : Range in number of days since present day}';
    // protected $signature = 'trevco:sync-orders {days_past}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync latest orders off integrated storefronts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->reportTransformer = new AmazonReportTransformer();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $daysPast = $this->argument('days_past');
        $persistenceService = new AmazonReportModelSync($this->reportTransformer);
        $service = new AmazonOrderSyncService($persistenceService, $daysPast);
        $service->execute($this->input, $this->output);
    }
}
