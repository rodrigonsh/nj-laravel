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

    private $uuid;

    /**
     * Create a new notification instance.
     */
    public function __construct(HelpRequest $req)
    {
        $this->uuid = $req->uuid;
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
        return (new FcmMessage(notification: new FcmNotification(
                title: 'Alguém precisa de ajuda',
                body: 'Você pode ajudar alguém?',
                image: 'http://example.com/url-to-image-here.png'
            )))
            ->data(['need' => $this->need, 'confession' => $this->confession])
            // add link
            ->custom([
                'fcm_options' => [
                    'link' => 'http://localhost:8100/i-want-to-help/'+$this->uuid
                ],
            ]);
            
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'need' => $this->need,
            'confession' => $this->confession,
            'title' => 'Alguém precisa de ajuda',
            'body' => 'Você pode ajudar alguém?',
            'image' => 'http://example.com/url-to-image-here.png'
        ];
    }
}
