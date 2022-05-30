<?php

declare(strict_types=1);

namespace App\Debricked\Events;

use App\Debricked\SourceInterface;

class QueueFailedEvent
{

    /**
     * @param SourceInterface $source
     */
    public function __construct(public readonly SourceInterface $source)
    {
    }
}