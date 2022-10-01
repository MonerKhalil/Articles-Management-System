<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $Data;
    public $user;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$Data,bool $NewPass = false)
    {
        $this->user = $user;
        $this->Data = $Data;
        $this->subject = $NewPass ? "Articles Site : New password for your email" : "Articles Site : Email verification";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->markdown('Activation_Code')->with([
            "data" => $this->Data,
            "user" => $this->user
        ]);
    }
}
