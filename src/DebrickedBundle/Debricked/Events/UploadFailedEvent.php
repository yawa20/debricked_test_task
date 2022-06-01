<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Events;

use DebrickedBundle\Debricked\SourceInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UploadFailedEvent extends Event
{
    public function __construct(
        public readonly SourceInterface $source,
        public readonly ?ResponseInterface $response = null,
        public readonly ?TransportExceptionInterface $exception = null,
    ) {
    }
}