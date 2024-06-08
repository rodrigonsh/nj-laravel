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

class HelpOnTheWay extends Notification
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
    Log::debug('HelpOnTheWay toFcm', ['uuid' => $this->uuid]);

    return (new FcmMessage(notification: new FcmNotification(
      title: 'Sua ajuda está a caminho!',
      body: 'Alguém está vindo te ajudar!',
    )))
      ->data([
        'type' => 'help-on-the-way',
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
      'title' => 'Sua ajuda está a caminho!',
      'body' => 'Alguém está vindo te ajudar!',
      'image' => '/images/logo.png',
    ];
  }
}
