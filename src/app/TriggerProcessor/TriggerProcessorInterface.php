<?php

declare(strict_types=1);

namespace App\TriggerProcessor;

use App\Entity\UploadEntity;
use App\TriggerProcessor\TriggerReason\TriggerReasonInterface;

interface TriggerProcessorInterface
{
    public function process(UploadEntity $entity, TriggerReasonInterface $reason);
}