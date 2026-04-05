<?php

namespace App\Listeners;

use App\Events\OperatorRegistered;
use App\Mail\OperatorWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOperatorWelcomeEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OperatorRegistered $event): void
    {
        Mail::to($event->user)
            ->send(new OperatorWelcomeMail($event->user));
    }
}
