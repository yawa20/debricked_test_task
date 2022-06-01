<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked;

use DebrickedBundle\Debricked\Api\DebrickedApiInterface;
use DebrickedBundle\Debricked\Events\ProcessProgressEvent;
use DebrickedBundle\Debricked\Events\QueueFailedEvent;
use DebrickedBundle\Debricked\Events\QueueSuccessEvent;
use DebrickedBundle\Debricked\Events\UploadFailedEvent;
use DebrickedBundle\Debricked\Events\UploadSuccessEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DebrickedProcessor implements DebrickedProcessorInterface
{
    public function __construct(
        private DebrickedApiInterface $api,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    /**
     * Maybe, it looks strange, but DebrickedProcessor SHOULD know about status changes
     * but SHOULD NOT know anything about database
     * so "flush" placed in another place
     *
     * @param SourceInterface $source
     * @param string $sourceDir
     *
     * @throws TransportExceptionInterface
     * @throws HttpExceptionInterface
     */
    public function uploadToDebricked(SourceInterface $source, string $sourceDir): void
    {
        $response = $this->api->uploadFile(
            filepath: $sourceDir.$source->getFilename(),
            repositoryName: $source->getRepositoryName(),
            commitName: $source->getCommitName()
        );

        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $exception) {
            $source->markFailed();
            $this->eventDispatcher->dispatch(new UploadFailedEvent(source: $source, exception: $exception));

            return;
        }
        if (200 === $statusCode) {
            $source->markUploaded();
            $ciUploadId = json_decode($response->getContent(false))->ciUploadId;
            $source->setCiUploadId($ciUploadId);

            $this->eventDispatcher->dispatch(new UploadSuccessEvent(source: $source));

            return;
        }

        $source->markFailed();
        $this->eventDispatcher->dispatch(new UploadFailedEvent(source: $source, response: $response));
    }

    public function queue(SourceInterface $source): void
    {
        $response = $this->api->queueUploads(
            ciUploadId: $source->getCiUploadId(),
            repositoryName: $source->getRepositoryName(),
            commitName: $source->getCommitName()
        );
        $statusCode = $response->getStatusCode();
        if (204 === $statusCode) {
            $source->setStatus(SourceInterface::STATUS__QUEUED);
            $this->eventDispatcher->dispatch(
                new QueueSuccessEvent($source)
            );

            return;
        }

        $this->eventDispatcher->dispatch(new QueueFailedEvent(source: $source));
    }

    public function requestStatus(SourceInterface $source): void
    {
        $response = $this->api->getStatus($source->getCiUploadId());
        $status = $response->getStatusCode();
        $source->progressTo(resultStatus: $status);
        $DTO = UploadStatusDTO::fromJson($response->getContent(), $status);
        $this->eventDispatcher->dispatch(new ProcessProgressEvent($source, $DTO));
    }
}