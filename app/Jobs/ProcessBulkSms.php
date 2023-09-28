<?php

namespace App\Jobs;

use App\Models\ActionLog;
use App\Services\AllServices\BulkSmsNg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessBulkSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $phone_numbers;

    protected $user_firstname;

    protected $msg;

    public function __construct($phone_numbers, $user_firstname, $msg, private ?string $from = 'Dinesurf', private ?array $action_log = null)
    {
        $this->phone_numbers = $phone_numbers;
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
        BulkSmsNg::sendSms($this->phone_numbers, $this->from, $this->msg, $this->user_firstname);
        if ($this->action_log) {
            ActionLog::create($this->action_log);
        }
    }
}
