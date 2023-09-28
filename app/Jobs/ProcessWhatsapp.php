<?php

namespace App\Jobs;

use App\Models\ActionLog;
use App\Services\AllServices\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $reservation;

    protected $type;

    protected $owner;

    public function __construct($reservation, $type, $owner, private ?array $action_log = null)
    {
        $this->reservation = $reservation;
        $this->type = $type;
        $this->owner = $owner;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->owner == 'vendor' && $this->type == 'created') {
            $content = null;
            try {
                $content = TwilioService::vendorResvationNotification($this->reservation, $this->type);
            } catch (\Throwable $th) {
                // throw $th;
                Log::error($th);
            }

            if ($this->action_log) {
                ActionLog::create(['content' => $content, ...$this->action_log]);
            }
        }
    }
}
