<?php

namespace App\Console\Commands;

use App\Services\AllServices\VendorEmails;
use Illuminate\Console\Command;

class SendVendorEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:vendoremails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            VendorEmails::day_after_signup();
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            VendorEmails::two_days_after_signup();
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            VendorEmails::seven_days_after_expiration();
        } catch (\Throwable $th) {
            throw $th;
        }

        return Command::SUCCESS;
    }
}
