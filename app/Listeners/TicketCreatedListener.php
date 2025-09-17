<?php

namespace App\Listeners;

use App\Events\TicketCreatedEvent;
use App\Notifications\TicketCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class TicketCreatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(TicketCreatedEvent $event): void
    {
        Notification::route('slack', '#requests-to-support')->notify(new TicketCreatedNotification($event->ticket));
    }
}
