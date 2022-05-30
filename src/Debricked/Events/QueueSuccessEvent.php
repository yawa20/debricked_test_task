<?php

declare(strict_types=1);

namespace App\Debricked\Events;

use App\Debricked\SourceInterface;
use Symfony\Contracts\EventDispatcher\Event;

class QueueSuccessEvent extends Event
{
    /**
     * @param SourceInterface $source
     */
    public function __construct(public readonly SourceInterface $source)
    {
    }
}