<?php

namespace App\Listeners;

use App\Events\QuoteRequestLodged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Mail\QuoteRequestNotification;
use Mail;
class QuoteRequesterNotify
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QuoteRequested  $event
     * @return void
     */
    public function handle(QuoteRequestLodged $event)
    {
        if (!$email = array_get($event->RFQ, "EmailAddress")) 
        {
            $email = array_get($event->RFQ, "Contact.EmailAddress");
        }

        if (!$name = array_get($event->RFQ, "Name"))
        {
            $name = implode(' ', array_filter([
                array_get($event->RFQ, "Contact.FirstName", ""),
                array_get($event->RFQ, "Contact.MiddleNames", ""),
                array_get($event->RFQ, "Contact.Surname", ""),
            ]));
        }

        if ($email) {
            Mail::to($email, $name)->send(new QuoteRequestNotification($name, $event->RFQ));
        }
    }
}
