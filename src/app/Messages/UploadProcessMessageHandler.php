<?php

declare(strict_types=1);

namespace App\Messages;

use App\Repository\UploadEntityRepository;
use App\ValueObject\Step;
use DebrickedBundle\Debricked\DebrickedProcessorInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UploadProcessMessageHandler
{
    public function __construct(
        private DebrickedProcessorInterface $processor,
        private UploadEntityRepository $repository,
        private string $uploadDir,
    ) {
    }

    /**
     * @param UploadProcessMessage $message
     * @uses startUpload
     * @uses queueItem
     * @uses getStatus
     */
    public function __invoke(UploadProcessMessage $message): void
    {
        $map = [
            Step::GET_STATUS->value => 'getStatus',
            Step::START_UPLOAD->value => 'startUpload',
            Step::QUEUE_ITEM->value => 'queueItem',
        ];

        if(array_key_exists($message->step->value, $map))
        {
            $functionName = $map[$message->step->value];
            $this->$functionName($message);
        }
    }

    private function startUpload(UploadProcessMessage $message)
    {
       $this->processor->uploadToDebricked(
           $this->repository->find($message->uploadEntityId),
           $this->uploadDir,
       );
    }

    private function queueItem(UploadProcessMessage $message)
    {
        $this->processor->queue(
            $this->repository->find($message->uploadEntityId),
        );
    }

    public function getStatus(UploadProcessMessage $message)
    {
        $this->processor->requestStatus(
            $this->repository->find($message->uploadEntityId),
        );
    }
}