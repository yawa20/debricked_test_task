<?php

declare(strict_types=1);

namespace App\TriggerProcessor\TriggerReason;

class FailureReason implements TriggerReasonInterface
{
    public function __construct(public readonly string $failureReason)
    {}
}