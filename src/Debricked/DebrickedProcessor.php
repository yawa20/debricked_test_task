<?php

declare(strict_types=1);

namespace App\Debricked;

use App\Debricked\Events\QueueFailedEvent;
use App\Debricked\Events\QueueSuccessEvent;
use App\Debricked\Events\UploadFailedEvent;
use App\Debricked\Events\ProcessFinishedEvent;
use App\Debricked\Events\ProcessProgressEvent;
use App\Debricked\Events\UploadSuccessEvent;
use App\DebrickedApi\DebrickedApi;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DebrickedProcessor implements DebrickedProcessorInterface
{
    public function __construct(
        private DebrickedApi $api,
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
            $data = json_decode($response->getContent(false));
            $source->setCiUploadId($data->ciUploadId);

            $this->eventDispatcher->dispatch(new UploadSuccessEvent(source: $source, response: $response));

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

        if (200 === $statusCode) {
            $this->eventDispatcher->dispatch(new QueueSuccessEvent(source: $source));

            return;
        }

        $this->eventDispatcher->dispatch(new QueueFailedEvent(source: $source));
    }

    public function requestStatus(SourceInterface $source): void
    {
        $response = $this->api->getStatus($source->getCiUploadId());
        $status = $response->getStatusCode();
        if (400 <= $status) {
            //todo handle errors
        }

        $data = json_decode($response->getContent(), true);
        $source->progressTo(resultStatus: $status, resultData: $data);
        $this->eventDispatcher->dispatch(new ProcessProgressEvent($source));

        if(200 === $status) {
            $this->eventDispatcher->dispatch(new ProcessFinishedEvent($source));
        }

    }
}