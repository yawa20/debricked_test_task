<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Debricked\Events\ProcessFinishedEvent;
use App\Debricked\Events\UploadFailedEvent;
use App\Entity\UploadEntity;
use App\Notifications\EmailNotification;
use App\Notifications\SlackNotification;
use App\ValueObject\Trigger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;

class NotificationEvenSubscriber implements EventSubscriberInterface
{
    public function __construct(private NotifierInterface $notifier)
    {}

    public static function getSubscribedEvents()
    {
        return [
            ProcessFinishedEvent::class => [
                ['onFinish', 50]
            ],
            UploadFailedEvent::class => [
                ['onUploadFailed', 50]
            ]
        ];
    }

    public function onFinish(ProcessFinishedEvent $event)
    {
        $entity = $event->source;
        if (!$entity instanceof UploadEntity) {
            return;
        }

        foreach ($entity->getTriggers() as $trigger) {
            if($trigger->type !== Trigger::TYPE__VUL_COUNT) {
                continue;
            }
            if ($entity->getUploadResult()->getVulnerabilitiesFound() < $trigger->triggerValue) {
                continue;
            }
            $notificationClass = $trigger->notificationType === Trigger::NOTIFICATION_TYPE_EMAIL ?
                    EmailNotification::class : SlackNotification::class;

            //todo render message prettier
            /** @var Notification $notification */
            $notification = new $notificationClass("there is vulnerability`s notification");
            $this->notifier->send($notification);
        }
    }

    public function onUploadFailed(UploadFailedEvent $event)
    {
        $entity = $event->source;
        if (!$entity instanceof UploadEntity) {
            return;
        }

        foreach ($entity->getTriggers() as $trigger) {
            if($trigger->type !== Trigger::TYPE__UPL_FAILED) {
                continue;
            }

            $notificationClass = $trigger->notificationType === Trigger::NOTIFICATION_TYPE_EMAIL ?
                EmailNotification::class : SlackNotification::class;

            //todo render message prettier
            /** @var Notification $notification */
            $notification = new $notificationClass("upload failed notification");
            $this->notifier->send($notification);
        }
    }
}