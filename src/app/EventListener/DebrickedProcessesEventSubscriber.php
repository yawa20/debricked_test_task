<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\UploadResult;
use App\Messages\UploadProcessMessage;
use App\Repository\UploadEntityRepository;
use App\Repository\UploadResultRepository;
use App\TriggerProcessor\TriggerProcessorInterface;
use App\TriggerProcessor\TriggerReason\FailureReason;
use App\TriggerProcessor\TriggerReason\FinishReason;
use App\ValueObject\Step;
use DebrickedBundle\Debricked\Events\ProcessProgressEvent;
use DebrickedBundle\Debricked\Events\QueueFailedEvent;
use DebrickedBundle\Debricked\Events\QueueSuccessEvent;
use DebrickedBundle\Debricked\Events\UploadFailedEvent;
use DebrickedBundle\Debricked\Events\UploadSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DebrickedProcessesEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UploadEntityRepository $repository,
        private UploadResultRepository $resultRepository,
        private MessageBusInterface $messageBus,
        private TriggerProcessorInterface $triggerProcessor,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UploadFailedEvent::class => [
                ['onUploadFailed', 100],
            ],
            UploadSuccessEvent::class => [
                ['onUploadSuccess', 100],
            ],
            QueueSuccessEvent::class => [
                ['onQueueSuccess', 100],
            ],
            QueueFailedEvent::class => [
                ['onQueueFailed', 100],
            ],
            ProcessProgressEvent::class => [
                ['onProgress', 100],
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
     * @see \DebrickedBundle\Debricked\DebrickedProcessor::uploadToDebricked
     */
    public function onUploadFailed(UploadFailedEvent $event)
    {
        $this->triggerProcessor->process(
            $event->source,
            new FailureReason($event->source->getId()." uploading was failed"),
        );
        $this->repository->flush();
    }

    public function onUploadSuccess(UploadSuccessEvent $event)
    {
        $this->repository->flush();
        $this->messageBus->dispatch(new UploadProcessMessage($event->source->getId(), Step::QUEUE_ITEM));
    }

    public function onQueueSuccess(QueueSuccessEvent $event)
    {
        $this->repository->flush();
        $this->messageBus->dispatch(new UploadProcessMessage($event->source->getId(), Step::GET_STATUS));

    }

    public function onQueueFailed(QueueFailedEvent $event)
    {
        $this->triggerProcessor->process(
            $event->source,
            new FailureReason($event->source->getId()." queuing was failed"),
        );
        $this->repository->flush();
    }

    public function onProgress(ProcessProgressEvent $event)
    {
        $event->source->progressTo($event->DTO->status);
        $uploadResult = $this->resultRepository->find($event->DTO->ciUploadId);
        if (null === $uploadResult) {
            $uploadResult = UploadResult::fromDTO($event->DTO);
            $this->resultRepository->add($uploadResult);
        } else {
            $uploadResult->update($event->DTO);
        }
        $this->repository->flush();
        if ($event->source->isFinished()) {
            $this->triggerProcessor->process($event->source, new FinishReason($uploadResult));
        }
        if (!$event->source->isFinished()) {
            $this->messageBus->dispatch(new UploadProcessMessage($event->source->getId(), Step::GET_STATUS));
        }
    }

}