<?php

namespace App\Console\Commands;

use App\Services\AllServices\General;
use Illuminate\Console\Command;

class UpdateReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Vendor Reviews From External Sources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            General::updateReviews();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
