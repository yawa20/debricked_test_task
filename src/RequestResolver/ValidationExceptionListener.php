<?php

declare(strict_types=1);

namespace App\RequestResolver;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ValidationExceptionListener
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        $requestFormat = $event->getRequest()->getRequestFormat();
        if (!$exception instanceof ValidationFailedException)
        {
            return;
        }
        $data = ['errors' => []];
        foreach ($exception->getViolations() as $violation)
        {
            /** @var ConstraintViolationInterface $violation */
            $data['errors'][] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
                ];
        }
        $event->setResponse(new JsonResponse($data));
    }
}