<?php

namespace App\Listeners;

use App\Events\SubmitInquiry;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\InquiryNotification as InquiryNotificationMail;
use Mail;

class InquiryNotification
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
     * @param  SubmitInquiry  $event
     * @return void
     */
    public function handle(SubmitInquiry $event)
    {
        Mail::to($event->inquiry->InquirerEmailAddress)->send(new InquiryNotificationMail($event->inquiry->InquirerName));
    }
}
