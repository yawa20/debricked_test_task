<?php

declare(strict_types=1);

namespace RequestResolverBundle\RequestResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Resolved Request data to RequestDTOInterface objects
 */
class RequestArgumentResolver implements ArgumentValueResolverInterface
{
    public function supports(
        Request $request,
        ArgumentMetadata $argument
    ): bool {
        if (!in_array(RequestDTOInterface::class, class_implements($argument->getType()))) {
            return false;
        }
        return true;
    }

    public function resolve(
        Request $request,
        ArgumentMetadata $argument
    ): iterable {
        /** @var RequestDTOInterface $class */
        $class = $argument->getType();
        yield $class::fromRequest($request);
    }
}
