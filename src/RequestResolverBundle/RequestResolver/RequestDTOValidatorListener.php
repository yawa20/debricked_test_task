<?php

declare(strict_types=1);

namespace RequestResolverBundle\RequestResolver;

use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDTOValidatorListener
{
    public function __construct(private ValidatorInterface $validator)
    {}

    public function onKernelControllerArguments(ControllerArgumentsEvent $event){
        $arguments = $event->getArguments();
        foreach ($arguments as $argument) {
            if ($argument instanceof RequestDTOInterface) {
                $constraints = $this->validator->validate($argument);
                if($constraints->count() > 0 ) {
                    throw new ValidationFailedException($argument, $constraints);
                }
            }
        }
    }
}