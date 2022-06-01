<?php

declare(strict_types=1);

namespace App\ValueObject;

class Trigger
{
    const TYPE__VUL_COUNT = 'vul_count';
    const TYPE__UPL_FAILED = 'upl_failed';
    const NOTIFICATION_TYPE_EMAIL = 'email';
    const NOTIFICATION_TYPE_SLACK = 'slack';

    public function __construct(
        public readonly string $type,
        public readonly string $notificationType,
        public readonly int $triggerValue = 0
    )
    {
    }
}