<?php

namespace App\Console\Commands;

use App\Services\AllServices\Admin;
use Illuminate\Console\Command;

class RemindReservationsin1hour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:reservationsin1hour';

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
            Admin::remindReservationsIn1Hour();
        } catch (\Throwable $th) {
            throw $th;
        }
        echo "\nReservations in 1 hour Reminded\n";

        return 0;
    }
}
