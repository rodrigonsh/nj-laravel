<?php

namespace App\Notifications;

use GPBMetadata\Google\Api\Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;
use Illuminate\Notifications\Notification;
use App\Models\HelpRequest;

use Illuminate\Support\Facades\Log;

class RequestHelp extends Notification
{
    use Queueable;

    private string $uuid;

    /**
     * Create a new notification instance.
     */
    public function __construct(HelpRequest $req)
    {
        $this->uuid = (string) $req->uuid;
        //Log::debug('RequestHelp constructor', ['uuid' => $this->uuid]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        Log::debug('RequestHelp toFcm', ['uuid' => $this->uuid]);

        // get users last door
        $user = auth()->user();
        $lastDoor = $user->lastdoor;

        return (new FcmMessage(notification: new FcmNotification(
                title: 'Alguém precisa de ajuda',
                body: 'Você pode ajudar com '.$user->need.'?',
            )))
            ->data([
                'type' => 'help-request', 
                'uuid' => $this->uuid,
                'lastDoor' => $lastDoor,
                ])
            ;
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'uuid' => $this->uuid,
            'title' => 'Alguém precisa de ajuda',
            'body' => 'Você pode ajudar alguém?',
            'image' => '/images/logo.png',
        ];
    }
}
