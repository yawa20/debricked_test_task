<?php

declare(strict_types=1);

namespace App\RequestDTO;

use Symfony\Component\Validator\Constraints as Assert;

class RuleDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['vul_count', 'upl_failed'], message: "allowed only 'vul_count', 'upl_failed' types")]
        public readonly mixed $trigger,
        #[Assert\NotBlank]
        #[Assert\Choice(choices: ['email', 'slack'], message: "allowed only 'vul_count', 'upl_failed' types")]
        public readonly mixed $notificationType,
        #[Assert\Type('integer')]
        public readonly mixed $triggerValue,
    ) {
    }
}