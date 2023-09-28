<?php

namespace App\Console\Commands;

use App\Services\AllServices\Admin;
use Illuminate\Console\Command;

class SendreviewMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendreview:mails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Review Mails';

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
            Admin::sendReviewMails();
        } catch (\Throwable $th) {
            throw $th;
        }
        echo "sent review emails\n";
    }
}
