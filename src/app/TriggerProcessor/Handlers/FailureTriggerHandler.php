<?php

declare(strict_types=1);

namespace App\TriggerProcessor\Handlers;

use App\Messages\NotificationMessage;
use App\Notifications\EmailNotification;
use App\Notifications\SlackNotification;
use App\TriggerProcessor\TriggerReason\FailureReason;
use App\TriggerProcessor\TriggerReason\TriggerReasonInterface;
use App\ValueObject\Trigger;
use Symfony\Component\Messenger\MessageBusInterface;

class FailureTriggerHandler implements TriggerHandlerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function supports(Trigger $trigger): bool
    {
        return Trigger::TYPE__UPL_FAILED === $trigger->type;
    }

    public function handle(Trigger $trigger, TriggerReasonInterface $reason): void
    {
        if (!$reason instanceof FailureReason) {
            return;
        }

        switch ($trigger->notificationType) {
            case Trigger::NOTIFICATION_TYPE_EMAIL:
                $this->sendEmail($reason->failureReason);
                break;
            case Trigger::NOTIFICATION_TYPE_SLACK:
                $this->sendSlack($reason->failureReason);
                break;
        }
    }

    private function sendEmail(string $reason): void
    {
        $notification = new EmailNotification("email notification");
        $notification->content($reason);
        $this->messageBus->dispatch(new NotificationMessage($notification));
    }

    private function sendSlack(string $reason): void
    {
        $notification = new SlackNotification("slack notification");
        $notification->content($reason);
        $this->messageBus->dispatch(new NotificationMessage($notification));
    }
}