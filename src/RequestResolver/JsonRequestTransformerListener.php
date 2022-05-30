<?php

declare(strict_types=1);

namespace App\RequestResolver;

use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonRequestTransformerListener
{
    public function onKernelRequest(RequestEvent $event) {
        $request = $event->getRequest();

        if ('json' !== $request->getContentType()) {
            return;
        }

        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        try {
            $data = json_decode($request->getContent(), true,  512,JSON_THROW_ON_ERROR);
            $request->request->add($data);
        } catch (JsonException $exception) {
            $response = new Response();
            $response->setStatusCode(400, 'Invalid Json Body.');
            $event->setResponse($response);
        }
    }
}