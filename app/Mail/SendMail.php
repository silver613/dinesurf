<?php

namespace App\Mail;

use App\Models\ActionLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $title;

    public $name;

    public $msg;

    public $file;

    /**
     * Create a new message instance.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function __construct($name, $title, $msg, $file = null, private ?array $action_log = null)
    {
        $this->name = $name;
        $this->title = $title;
        $this->msg = $msg;
        $this->file = $file;
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
        if ($this->file) {
            return $this->subject($this->title)
                ->view('mail.index')
                ->attachFromStorageDisk('s3', $this->file);
        } else {
            return $this->subject($this->title)
                ->view('mail.index');
        }
    }
}
