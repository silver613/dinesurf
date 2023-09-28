<?php

namespace App\Console\Commands;

use App\Services\AllServices\General;
use Illuminate\Console\Command;

class SyncGuests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:guests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Users Table To Guests';

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
            General::syncGuests();
        } catch (\Throwable $th) {
            throw $th;
        }
        echo "done\n";
    }
}
