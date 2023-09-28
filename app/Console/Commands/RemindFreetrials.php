<?php

namespace App\Console\Commands;

use App\Services\AllServices\Admin;
use Illuminate\Console\Command;

class RemindFreetrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:freetrials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remind free trila vendors a day before free trial ends';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            Admin::freeTrialAlmostEndedNotification();
        } catch (\Throwable $th) {
            throw $th;
        }
        echo "done\n";
    }
}
