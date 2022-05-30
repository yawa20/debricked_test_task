<?php

declare(strict_types=1);

namespace App\Messages;

use App\Debricked\DebrickedProcessor;
use App\Repository\UploadEntityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class GetStatusMessageHandler
{
    public function __construct(
        private DebrickedProcessor $processor,
        private UploadEntityRepository $repository,
    )
    {}

    public function __invoke(GetStatusMessage $message)
    {
        $uploadEntity = $this->repository->find($message->uploadEntityId);
        $this->processor->requestStatus($uploadEntity);
    }
}