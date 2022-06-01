<?php

declare(strict_types=1);

namespace App\TriggerProcessor\TriggerReason;

use App\Entity\UploadResult;

class FinishReason implements TriggerReasonInterface
{
    public function __construct(public readonly UploadResult $result)
    {
    }
}