<?php

declare(strict_types=1);

namespace App\Messages;

class QueueEntityMessage
{
    public function __construct(public readonly int $uploadEntityId)
    {
    }
}