<?php

namespace App\Jobs;

use App\Models\ActionLog;
use App\Services\AllServices\BulkSmsNg;
use App\Services\AllServices\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $phone_number;

    protected $user_firstname;

    protected $msg;

    public function __construct($phone_number, $user_firstname, $msg, private ?array $action_log = null)
    {
        $this->phone_number = $phone_number;
        $this->user_firstname = $user_firstname;
        $this->msg = $msg;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TwilioService::sendSms($this->phone_number, $this->msg, $this->user_firstname);
        BulkSmsNg::sendSms($this->phone_number, 'Dinesurf', $this->msg, $this->user_firstname);
        if ($this->action_log) {
            ActionLog::create($this->action_log);
        }
    }
}
