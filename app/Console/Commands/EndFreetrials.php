<?php

namespace App\Console\Commands;

use App\Services\AllServices\Admin;
use Illuminate\Console\Command;

class EndFreetrials extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'end:freetrials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'end free trials';

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
            Admin::endFreetrial();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
