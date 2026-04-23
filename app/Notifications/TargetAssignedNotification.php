<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TargetAssignedNotification extends Notification
{
    use Queueable;

    public $target;

    public function __construct($target)
    {
        $this->target = $target;
    }

    // কোন channel use হবে
    public function via($notifiable)
    {
        return ['database']; // 🔥 DB notification
    }

    // DB তে কী save হবে
    public function toArray($notifiable)
    {
        return [
            'message' => 'New target assigned',
            'month' => $this->target->month,
            'target_value' => $this->target->target_value,
        ];
    }
}
