<?php

declare(strict_types=1);

namespace App\TriggerProcessor\Handlers;

use App\TriggerProcessor\TriggerReason\TriggerReasonInterface;
use App\ValueObject\Trigger;

interface TriggerHandlerInterface
{
    public function supports(Trigger $trigger): bool;

    public function handle(Trigger $trigger, TriggerReasonInterface $reason): void;
}