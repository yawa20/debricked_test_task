<?php

declare(strict_types=1);

namespace App\Debricked\Events;

use App\Debricked\SourceInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UploadSuccessEvent extends Event
{
    public function __construct(
        public readonly SourceInterface $source,
        public readonly ResponseInterface $response
    ) {
    }
}