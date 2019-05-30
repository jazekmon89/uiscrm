<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuoteRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, Array $RFQ)
    {
        $this->subject = view('emailTemplates.RFQ.subject.text', ['RFQ'=>$RFQ, 'name'=>$name])->render();
        $this->name = $name;
        $this->RFQ = $RFQ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emailTemplates.RFQ.Lodge', ['name' => $this->name, 'RFQ' => $this->RFQ])->subject($this->subject);
    }
}
