<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Events;

use DebrickedBundle\Debricked\SourceInterface;

class ProcessFinishedEvent
{
    public function __construct(
        public readonly SourceInterface $source
    ) {
    }
}