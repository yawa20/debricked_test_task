<?php

declare(strict_types=1);

namespace App\Messages;

use App\Debricked\DebrickedProcessor;
use App\Repository\UploadEntityRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class StartUploadMessageHandler
{
    public function __construct(
        private DebrickedProcessor $processor,
        private UploadEntityRepository $repository,
        private string $uploadDir,
    ) {
    }

    public function __invoke(StartUploadMessage $message)
    {
        $uploadEntity = $this->repository->find($message->uploadEntityId);
        $this->processor->uploadToDebricked($uploadEntity, $this->uploadDir);
    }
}