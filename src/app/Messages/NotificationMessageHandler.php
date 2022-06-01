<?php

declare(strict_types=1);

namespace App\Messages;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Notifier\NotifierInterface;

#[AsMessageHandler]
class NotificationMessageHandler
{
    public function __construct(
        private NotifierInterface $notifier,
    ) {
    }

    public function __invoke(NotificationMessage $message): void
    {
        $this->notifier->send($message->notification);
    }
}