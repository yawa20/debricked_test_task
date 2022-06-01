<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Events;

use DebrickedBundle\Debricked\SourceInterface;
use DebrickedBundle\Debricked\UploadStatusDTO;

class ProcessProgressEvent
{
    public function __construct(
        public readonly SourceInterface $source,
        public readonly UploadStatusDTO $DTO,
    ) {
    }
}