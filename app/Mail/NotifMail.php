<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('emails.notif-mail');

        if (!empty($this->details['reply'])) {
            $mail->from($this->details['reply'], 'Esellexpress');
        } else {
            $mail->subject($this->details['title']);
        }
    
        return $mail;
    }
}
