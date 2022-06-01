<?php

declare(strict_types=1);

namespace App\TriggerProcessor;

use App\Entity\UploadEntity;
use App\TriggerProcessor\Handlers\TriggerHandlerInterface;
use App\TriggerProcessor\TriggerReason\TriggerReasonInterface;

class TriggerProcessor implements TriggerProcessorInterface
{
    /** @var TriggerHandlerInterface[] */
    private array $handlers = [];

    public function addHandler(TriggerHandlerInterface $handler)
    {
        $this->handlers[get_class($handler)] = $handler;
    }

    public function process(UploadEntity $entity, TriggerReasonInterface $reason)
    {
        foreach ($entity->getTriggers() as $trigger)
        {
            foreach ($this->handlers as $handler) {
                if(!$handler->supports($trigger)) {
                    continue;
                }
                $handler->handle($trigger, $reason);
            }
        }
    }
}