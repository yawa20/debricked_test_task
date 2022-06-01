<?php

declare(strict_types=1);

namespace DebrickedBundle\Debricked\Api;

use DebrickedBundle\Debricked\Api\Client\ApiClientInterface;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\ResponseInterface;

class DebrickedApi implements DebrickedApiInterface
{
    public function __construct(private ApiClientInterface $apiClient)
    {
    }

    public function uploadFile(
        string $filepath,
        string $repositoryName,
        string $commitName,
    ): ResponseInterface
    {
        $data = [
            'fileData' =>  DataPart::fromPath($filepath),
            'repositoryName' => $repositoryName,
            'commitName' => $commitName
        ];
        $formData = new FormDataPart($data);
        return $this->apiClient->post(
            '/open/uploads/dependencies/files',
            $formData->bodyToIterable(),
            $formData->getPreparedHeaders()->toArray()
        );
    }

    public function queueUploads(
        int $ciUploadId,
        string $repositoryName,
        string $commitName
    ): ResponseInterface
    {
        $data = [
            "ciUploadId" => $ciUploadId,
            "repositoryName" => $repositoryName,
            "commitName" => $commitName,
        ];

        return  $this->apiClient->post('/open/finishes/dependencies/files/uploads', $data);
    }

    public function getStatus(int $ciUploadId): ResponseInterface
    {
        return $this->apiClient->get('/open/ci/upload/status', ['ciUploadId' => $ciUploadId]);
    }
}