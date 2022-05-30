<?php

declare(strict_types=1);

namespace App\Notifications;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

class EmailNotification extends Notification
{
    public function getChannels(RecipientInterface $recipient): array
    {
        return ['email'];
    }
}