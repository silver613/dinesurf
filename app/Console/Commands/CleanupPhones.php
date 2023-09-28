<?php

namespace App\Console\Commands;

use App\Services\AllServices\Admin;
use Illuminate\Console\Command;

class CleanupPhones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:phones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            Admin::cleanupPhones();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
