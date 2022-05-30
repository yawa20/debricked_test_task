<?php

declare(strict_types=1);

namespace App\RequestResolver;

use Symfony\Component\HttpFoundation\Request;

interface RequestDTOInterface
{
    public static function fromRequest(Request $request): self;
}