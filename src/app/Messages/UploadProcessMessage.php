<?php

declare(strict_types=1);

namespace App\Messages;

use App\ValueObject\Step;

class UploadProcessMessage
{
    public function __construct(public readonly int $uploadEntityId, public readonly Step $step)
    {
    }
}