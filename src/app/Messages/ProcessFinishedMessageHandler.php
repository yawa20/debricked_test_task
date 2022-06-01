<?php

declare(strict_types=1);

namespace App\Messages;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ProcessFinishedMessageHandler
{
    public function __invoke(ProcessFinishedMessage $message): void
    {

    }
}