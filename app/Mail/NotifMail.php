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
        if(!empty($this->details['reply'])){
            return $this->from($this->details['reply'],'Esellexpress')
            ->view('emails.notif-mail');
        }else{
            return $this->subject($this->details['title'])
            ->view('emails.notif-mail');
        }
    }
}
