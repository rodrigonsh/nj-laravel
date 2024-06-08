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

class MeetYourPartner extends Notification
{
  use Queueable;

  private string $uuid;

  /**
   * Create a new notification instance.
   */
  public function __construct(HelpRequest $req)
  {
    $this->uuid = (string) $req->uuid;
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
    Log::debug('MeetYourPartner toFcm', ['uuid' => $this->uuid]);

    return (new FcmMessage(notification: new FcmNotification(
      title: 'Conheça seu parceiro!',
      body: 'Você foi conectado com alguém para ajudar!',
    )))
      ->data([
        'type' => 'meet-your-partner',
        'uuid' => $this->uuid,
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
      'uuid' => $this->uuid,
      'title' => 'Conheça seu parceiro!',
      'body' => 'Você foi conectado com alguém para ajudar!',
      'image' => '/images/logo.png',
    ];
  }
}
