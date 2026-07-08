<?php

namespace App\Traits;

trait DatabaseNotificationTrait
{
    /**
     * Common structure for database notifications.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title'    => $this->getTitle(),
            'message'  => $this->getMessage(),
            'type'     => $this->getType(),
            'link'     => $this->getLink(),
            'created_at' => now(),
        ];
    }
}
