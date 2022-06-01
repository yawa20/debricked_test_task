<?php

declare(strict_types=1);

namespace App\Messages;

use Symfony\Component\Notifier\Notification\Notification;

class NotificationMessage
{
    public function __construct(public readonly Notification $notification)
    {
    }
}