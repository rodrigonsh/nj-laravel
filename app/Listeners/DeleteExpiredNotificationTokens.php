<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DeleteExpiredNotificationTokens
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
    public function handle(object $event): void
    {
        $report = Arr::get($event->data, 'report');

        $target = $report->target();

        Log::info('DeleteExpiredNotificationTokens: '.json_encode($event));
        /*
        $event->notifiable->notificationTokens()
            ->where('push_token', $target->value())
            ->delete();*/
    }
}