<?php

declare(strict_types=1);

namespace App\Messages;

class StartUploadMessage
{
    public function __construct(public readonly int $uploadEntityId)
    {
    }
}