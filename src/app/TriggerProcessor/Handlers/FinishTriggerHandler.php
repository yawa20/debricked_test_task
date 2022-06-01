<?php

declare(strict_types=1);

namespace App\TriggerProcessor\Handlers;

use App\Entity\UploadResult;
use App\Messages\NotificationMessage;
use App\Notifications\EmailNotification;
use App\Notifications\SlackNotification;
use App\TriggerProcessor\TriggerReason\FinishReason;
use App\TriggerProcessor\TriggerReason\TriggerReasonInterface;
use App\ValueObject\Trigger;
use Symfony\Component\Messenger\MessageBusInterface;

class FinishTriggerHandler implements TriggerHandlerInterface
{
    public function __construct(
        private MessageBusInterface $messageBus,
    ) {
    }

    public function supports(Trigger $trigger): bool
    {
        return Trigger::TYPE__VUL_COUNT === $trigger->type;
    }

    public function handle(Trigger $trigger, TriggerReasonInterface $reason): void
    {
        if (!$reason instanceof FinishReason) {
            return;
        }
        if (!$this->accepts($trigger, $reason->result)) {
            return;
        }

        switch ($trigger->notificationType) {
            case Trigger::NOTIFICATION_TYPE_EMAIL:
                $this->sendEmail($trigger, $reason->result);
                break;
            case Trigger::NOTIFICATION_TYPE_SLACK:
                $this->sendSlack($trigger, $reason->result);
                break;
        }
    }

    private function accepts(Trigger $trigger, UploadResult $result): bool
    {
        return $result->getVulnerabilitiesFound() > $trigger->triggerValue;
    }

    private function sendEmail(Trigger $trigger, UploadResult $result): void
    {
        $notification = new EmailNotification("email notification");
        $notification->content(
            "Vulnerabilities found{$result->getVulnerabilitiesFound()}, triggered after $trigger->triggerValue"
        );
        $this->messageBus->dispatch(new NotificationMessage($notification));
    }

    private function sendSlack(Trigger $trigger, UploadResult $result): void
    {
        $notification = new SlackNotification("slack notification");
        $notification->content(
            "Vulnerabilities found{$result->getVulnerabilitiesFound()}, triggered after $trigger->triggerValue"
        );
        $this->messageBus->dispatch(new NotificationMessage($notification));
    }
}