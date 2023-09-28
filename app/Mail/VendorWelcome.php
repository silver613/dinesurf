<?php

namespace App\Mail;

use App\Models\ActionLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public object $vendor)
    {
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        ActionLog::create([
            'vendor_id' => $this->vendor->id,
            'route' => 'email',
            'type' => 'vendor_welcome',
        ]);

        return $this->view('mail.vendor_welcome');
    }
}
