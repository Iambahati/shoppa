<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ShoppaNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $title,
        public readonly string $message,
        public readonly string $url,
        public readonly string $icon     = 'bell',
        public readonly string $priority = 'info',  // info | warning | critical | success
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title'    => $this->title,
            'message'  => $this->message,
            'url'      => $this->url,
            'icon'     => $this->icon,
            'priority' => $this->priority,
        ];
    }
}
