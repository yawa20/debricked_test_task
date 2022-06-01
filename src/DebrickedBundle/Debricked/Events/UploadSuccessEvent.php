<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Events;

use DebrickedBundle\Debricked\SourceInterface;
use Symfony\Contracts\EventDispatcher\Event;

class UploadSuccessEvent extends Event
{
    public function __construct(
        public readonly SourceInterface $source
    ) {
    }
}