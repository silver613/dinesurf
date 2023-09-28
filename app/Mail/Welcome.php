<?php

namespace App\Mail;

use App\Models\ActionLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Welcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        ActionLog::create([
            'user_id' => $this->user->id,
            'route' => 'email',
            'type' => 'user_welcome',
        ]);

        return $this->view('mail.welcome_v2')->subject('Welcome to DineSurf');
    }
}
