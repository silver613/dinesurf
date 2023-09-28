<?php

namespace App\Mail;

use App\Models\ActionLog;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject;

    public $banner;

    public $msg;

    public $vendor;

    public $name;

    public function __construct($name, $vendor, $subject, $banner, $msg, private ?array $action_log = null)
    {
        $this->name = $name;
        $this->vendor = $vendor;
        $this->subject = $subject;
        $this->banner = $banner;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->action_log) {
            ActionLog::create($this->action_log);
        }

        return $this->subject($this->subject)
            ->view('mail.template');
    }
}
