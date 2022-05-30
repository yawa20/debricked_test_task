<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Debricked\Events\ProcessFinishedEvent;
use App\Debricked\Events\ProcessProgressEvent;
use App\Debricked\Events\QueueFailedEvent;
use App\Debricked\Events\QueueSuccessEvent;
use App\Debricked\Events\UploadFailedEvent;
use App\Debricked\Events\UploadSuccessEvent;
use App\Entity\UploadResult;
use App\Messages\QueueEntityMessage;
use App\Repository\UploadEntityRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DebrickedProcessesEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UploadEntityRepository $repository,
        private MessageBusInterface $messageBus
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            UploadFailedEvent::class => [
                ['onUploadFailed', 100],
            ],
            UploadSuccessEvent::class => [
                ['onUploadSuccess', 100],
            ],
            ProcessProgressEvent::class => [
                ['onProgress', 100],
            ],
            ProcessFinishedEvent::class => [
                ['onFinish', 100],
            ],
        ];
    }

    /**
     * Here is main app functionality
     * this code knows about database storage (and about Entity Manager)
     * but not responsible about status changing
     *
     * @param UploadFailedEvent $event
     *
     * @see \App\Debricked\DebrickedProcessor::uploadToDebricked
     */
    public function onUploadFailed(UploadFailedEvent $event)
    {
        $this->repository->flush();
    }

    public function onUploadSuccess(UploadSuccessEvent $event)
    {
        $uploadResult = new UploadResult($event->source);
        $this->repository->flush();

        $this->messageBus->dispatch(new QueueEntityMessage($event->source->getId()));
    }

    public function onCommitSuccessEvent(QueueSuccessEvent $event)
    {
        //todo: write something
    }

    public function onCommitFailedEvent(QueueFailedEvent $event)
    {
        //todo: write something
    }

    public function onProgress()
    {
        $this->repository->flush();
    }

    public function onFinish()
    {

    }
}